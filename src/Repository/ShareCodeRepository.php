<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramShareBundle\Entity\ShareCode;

/**
 * @extends ServiceEntityRepository<ShareCode>
 */
#[AsRepository(entityClass: ShareCode::class)]
class ShareCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareCode::class);
    }

    public function save(ShareCode $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShareCode $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return list<ShareCode>
     */
    public function findByValidTrue(): array
    {
        $queryResult = $this->createQueryBuilder('s')
            ->andWhere('s.valid = :valid')
            ->setParameter('valid', true)
            ->getQuery()
            ->getResult()
        ;

        if (!is_array($queryResult)) {
            return [];
        }

        /** @var list<ShareCode> */
        return array_values(array_filter($queryResult, static fn ($item): bool => $item instanceof ShareCode));
    }
}
