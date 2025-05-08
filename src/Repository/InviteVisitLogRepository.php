<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

/**
 * @method InviteVisitLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method InviteVisitLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method InviteVisitLog[]    findAll()
 * @method InviteVisitLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InviteVisitLogRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InviteVisitLog::class);
    }
}
