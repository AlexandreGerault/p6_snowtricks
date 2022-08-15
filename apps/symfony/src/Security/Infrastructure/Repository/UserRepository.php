<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Repository;

use App\Security\Infrastructure\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends  ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements \App\Security\Core\UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByActivationToken(string $token): ?User
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

        return $user;
    }

    public function save(\App\Security\Core\User $user): void
    {
        $snapshot = $user->snapshot();

        $entity = new User();
        $entity->setEmail($snapshot->email());
        $entity->setUsername($snapshot->username());
        $entity->setPassword($snapshot->password()->value);

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
}
