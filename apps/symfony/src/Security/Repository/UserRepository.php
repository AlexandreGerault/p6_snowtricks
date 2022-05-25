<?php

declare(strict_types=1);

namespace App\Security\Repository;

use App\Security\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends  ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $token
     * @return User|null
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
            throw new \Exception("The user type is invalid");
        }

        return $user;
    }
}
