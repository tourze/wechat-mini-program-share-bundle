<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

/**
 * @extends ServiceEntityRepository<InviteVisitLog>
 *
 * @method InviteVisitLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method InviteVisitLog|null findOneBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null)
 * @method InviteVisitLog[]    findAll()
 * @method InviteVisitLog[]    findBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null, $limit = null, $offset = null)
 */
class InviteVisitLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InviteVisitLog::class);
    }

    /**
     * @return InviteVisitLog[]
     */
    public function findByValidTrue(): array
    {
        /** @var InviteVisitLog[] $result */
        $result = $this->createQueryBuilder('i')
            ->andWhere('i.valid = :valid')
            ->setParameter('valid', true)
            ->getQuery()
            ->getResult();
        
        return $result;
    }
}
