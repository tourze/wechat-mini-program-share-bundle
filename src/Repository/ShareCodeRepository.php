<?php

namespace WechatMiniProgramShareBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramShareBundle\Entity\ShareCode;

/**
 * @extends ServiceEntityRepository<ShareCode>
 *
 * @method ShareCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShareCode|null findOneBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null)
 * @method ShareCode[]    findAll()
 * @method ShareCode[]    findBy(array<string, mixed> $criteria, array<string, string>|null $orderBy = null, $limit = null, $offset = null)
 */
class ShareCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShareCode::class);
    }

    /**
     * @return ShareCode[]
     */
    public function findByValidTrue(): array
    {
        /** @var ShareCode[] $result */
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.valid = :valid')
            ->setParameter('valid', true)
            ->getQuery()
            ->getResult();
        
        return $result;
    }
}
