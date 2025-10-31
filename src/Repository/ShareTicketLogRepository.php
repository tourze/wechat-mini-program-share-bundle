<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

/**
 * @extends ServiceEntityRepository<ShareTicketLog>
 */
#[AsRepository(entityClass: ShareTicketLog::class)]
class ShareTicketLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareTicketLog::class);
    }

    public function save(ShareTicketLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShareTicketLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return list<ShareTicketLog>
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

        /** @var list<ShareTicketLog> */
        return array_values(array_filter($queryResult, static fn ($item): bool => $item instanceof ShareTicketLog));
    }
}
