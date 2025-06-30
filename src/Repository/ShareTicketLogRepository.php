<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

/**
 * @extends ServiceEntityRepository<ShareTicketLog>
 *
 * @method ShareTicketLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShareTicketLog|null findOneBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null)
 * @method ShareTicketLog[]    findAll()
 * @method ShareTicketLog[]    findBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null, $limit = null, $offset = null)
 */
class ShareTicketLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareTicketLog::class);
    }

    /**
     * @return ShareTicketLog[]
     */
    public function findByValidTrue(): array
    {
        /** @var ShareTicketLog[] $result */
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.valid = :valid')
            ->setParameter('valid', true)
            ->getQuery()
            ->getResult();
        
        return $result;
    }
}
