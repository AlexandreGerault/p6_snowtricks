<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Repository;

use App\Security\Core\HashedPassword;
use App\Security\Core\ActivationToken;
use App\Security\Core\User as CoreUser;
use App\Security\Infrastructure\Entity\User;
use App\Trick\Infrastructure\Entity\Trick as Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\AbstractUid;

/**
 * @extends  ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements \App\Security\Core\UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(CoreUser $user): void
    {
        $snapshot = $user->snapshot();

        /** @var User $entity */
        $entity = $this->findOneBy(['uuid' => $snapshot->id]) ?? new User();

        $entity->setEmail($snapshot->email);
        $entity->setUsername($snapshot->username);
        $entity->setPassword($snapshot->password->value);
        $entity->setActive($snapshot->activated);

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function exists(string $email): bool
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function get(AbstractUid $id): CoreUser
    {
        // TODO: Implement get() method.
    }

    public function getFromActivationToken(ActivationToken $token): ?CoreUser
    {
        $user = $this->createQueryBuilder('u')
            ->innerJoin('u.activationToken', 'a')
            ->where('a.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();

        if (!($user instanceof User) && !is_null($user)) {
            throw new \Exception('The user type is invalid');
        }

        if (is_null($user)) {
            return null;
        }

        if (is_null($user->activationToken())) {
            return throw new \Exception('The user activation token is null');
        }

        return new CoreUser(
            id: $user->id(),
            username: $user->username(),
            email: $user->email(),
            password: new HashedPassword($user->getPassword()),
            activated: $user->isActive(),
            activationToken: new ActivationToken($user->activationToken()->getToken())
        );
    }
}
