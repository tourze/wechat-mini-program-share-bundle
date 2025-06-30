<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

/**
 * @extends ServiceEntityRepository<ShareVisitLog>
 *
 * @method ShareVisitLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShareVisitLog|null findOneBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null)
 * @method ShareVisitLog[]    findAll()
 * @method ShareVisitLog[]    findBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null, $limit = null, $offset = null)
 */
class ShareVisitLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareVisitLog::class);
    }

    /**
     * @return ShareVisitLog[]
     */
    public function findByValidTrue(): array
    {
        /** @var ShareVisitLog[] $result */
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.valid = :valid')
            ->setParameter('valid', true)
            ->getQuery()
            ->getResult();
        
        return $result;
    }
}
