<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

/**
 * @method ShareVisitLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShareVisitLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShareVisitLog[]    findAll()
 * @method ShareVisitLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShareVisitLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareVisitLog::class);
    }
}
