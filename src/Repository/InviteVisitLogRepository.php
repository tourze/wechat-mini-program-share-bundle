<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

/**
 * @extends ServiceEntityRepository<InviteVisitLog>
 */
#[AsRepository(entityClass: InviteVisitLog::class)]
class InviteVisitLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InviteVisitLog::class);
    }

    public function save(InviteVisitLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InviteVisitLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return list<InviteVisitLog>
     */
    public function findByNewUserTrue(): array
    {
        $queryResult = $this->createQueryBuilder('i')
            ->andWhere('i.newUser = :newUser')
            ->setParameter('newUser', true)
            ->getQuery()
            ->getResult()
        ;

        if (!is_array($queryResult)) {
            return [];
        }

        /** @var list<InviteVisitLog> */
        return array_values(array_filter($queryResult, static fn ($item): bool => $item instanceof InviteVisitLog));
    }
}
