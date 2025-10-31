<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

/**
 * @extends ServiceEntityRepository<ShareVisitLog>
 */
#[AsRepository(entityClass: ShareVisitLog::class)]
class ShareVisitLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareVisitLog::class);
    }

    public function save(ShareVisitLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShareVisitLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return list<ShareVisitLog>
     */
    public function findByValidTrue(): array
    {
        $queryResult = $this->createQueryBuilder('s')
            ->getQuery()
            ->getResult()
        ;

        if (!is_array($queryResult)) {
            return [];
        }

        /** @var list<ShareVisitLog> */
        return array_values(array_filter($queryResult, static fn ($item): bool => $item instanceof ShareVisitLog));
    }
}
