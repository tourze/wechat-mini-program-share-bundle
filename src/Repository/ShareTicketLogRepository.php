<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

/**
 * @method ShareTicketLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShareTicketLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShareTicketLog[]    findAll()
 * @method ShareTicketLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShareTicketLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareTicketLog::class);
    }
}
