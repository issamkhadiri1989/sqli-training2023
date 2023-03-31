<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * Gets all accounts that have not been used for $days day period.
     *
     * @param int $days
     * @param array $excludedIds
     *
     * @return User[]
     *
     * @throws \Exception
     */
    public function retrieveOldAccounts(int $days, array $excludedIds): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        // get back $days days in the past
        $limitDate = new \DateTime((-$days) . ' days');

        $query = $queryBuilder
            ->select('u')
            ->where('u.lastConnectionTime < :date')
            ->andWhere('u.enabled = :status')
            ->setParameter('date', $limitDate)
            ->setParameter('status', true);

        if (!empty($excludedIds)) {
            $expression = $queryBuilder->expr();
            $excludeIdsCondition = $expression->notIn('u.id', ':excludedIds');
            $query->andWhere($excludeIdsCondition)
                ->setParameter('excludedIds', $excludedIds);
        }

        return $query
            ->getQuery()
            ->getResult();
    }


    /**
     * This function tries to build the following query:
     *     `select * from user where enabled=0 or (enable=1 and last_connection_time > '2023-01-001').`
     *
     * @return void
     */
    public function getDisabledOrEnabledAccountThisYear(): void
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $expression = $queryBuilder->expr();
        $disabledAccountsCondition = $expression->eq('u.enabled', ':param1');
        $enabledAccountsCondition = $expression->eq('u.enabled', ':param2');
        $lastConnectedCondition = $expression->gt('u.lastConnectionTime', ':param3');

        // (enabled=1 and last_connection_time > '2023-01-01')
        $condition1 = $expression->andX($enabledAccountsCondition, $lastConnectedCondition);

        // enabled=0 or ($condition1)
        $condition2 = $expression->orX($disabledAccountsCondition, $condition1);

        // where ($condition2 or ($condition1))
        $queryBuilder->where($condition2)
            /*->where($disabledAccountsCondition)
            ->orWhere($condition1)*/
            ->setParameter('param1', false)
            ->setParameter('param2', true)
            ->setParameter('param3', new \DateTimeImmutable('2023-01-01'))
            ->getQuery()
            ->getResult();
    }
}
