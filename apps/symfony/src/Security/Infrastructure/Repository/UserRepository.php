<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Repository;

use App\Security\Core\ActivationToken as CoreActivationToken;
use App\Security\Core\HashedPassword;
use App\Security\Core\User as CoreUser;
use App\Security\Infrastructure\Entity\ActivationToken;
use App\Security\Infrastructure\Entity\PasswordResetToken;
use App\Security\Infrastructure\Entity\User;
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

        if (!is_null($snapshot->activationToken)) {
            $activationToken = $entity->activationToken() ?? new ActivationToken();
            $activationToken->setToken($snapshot->activationToken->token);
            $this->_em->persist($activationToken);

            $entity->setActivationToken($activationToken);
        }

        if (!is_null($snapshot->passwordResetToken)) {
            $passwordResetToken = $entity->passwordResetToken() ?? new PasswordResetToken();
            $passwordResetToken->setToken($snapshot->passwordResetToken->token);
            $this->_em->persist($passwordResetToken);

            $entity->setPasswordResetToken($passwordResetToken);
        }

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

    public function get(AbstractUid $id): ?CoreUser
    {
        $doctrineUser = $this->findOneBy(['uuid' => $id]);

        if (is_null($doctrineUser)) {
            return null;
        }

        return $this->coreUser($doctrineUser);
    }

    /** @throws NonUniqueResultException
     * @throws \Exception
     */
    public function getFromActivationToken(CoreActivationToken $token): ?CoreUser
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

        return $this->coreUser($user);
    }

    public function findByEmail(string $email): ?CoreUser
    {
        $entity = $this->findOneBy(['email' => $email]);

        if (is_null($entity)) {
            return null;
        }

        return $this->coreUser($entity);
    }

    private function coreUser(User $entity): CoreUser
    {
        return new CoreUser(
            id: $entity->id(),
            username: $entity->username(),
            email: $entity->email(),
            password: new HashedPassword($entity->getPassword()),
            activated: $entity->isActive(),
            activationToken: $entity->activationToken() ? new CoreActivationToken($entity->activationToken()->getToken()) : null
        );
    }
}
