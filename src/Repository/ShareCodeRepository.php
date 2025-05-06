<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\ShareCode;

/**
 * @method ShareCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShareCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShareCode[]    findAll()
 * @method ShareCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShareCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareCode::class);
    }
}
