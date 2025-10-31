<?php

namespace WechatMiniProgramShareBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;
use WechatMiniProgramShareBundle\Repository\ShareTicketLogRepository;

/**
 * @internal
 */
#[CoversClass(ShareTicketLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShareTicketLogRepositoryTest extends AbstractRepositoryTestCase
{
    private ShareTicketLogRepository $repository;

    protected function createNewEntity(): object
    {
        $entity = new ShareTicketLog();

        // 设置必填字段
        $entity->setMemberId(1);
        $entity->setShareMemberId(2);
        $entity->setOpenGid('test_gid_' . uniqid());
        $entity->setCreateTime(new \DateTimeImmutable());

        return $entity;
    }

    /**
     * @return ShareTicketLogRepository
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ShareTicketLogRepository::class);
    }

    public function testSave(): void
    {
        $entity = new ShareTicketLog();
        $entity->setMemberId(123);
        $entity->setShareMemberId(456);
        $entity->setOpenGid('test_open_gid');
        $entity->setCreateTime(new \DateTimeImmutable());

        $this->repository->save($entity);

        $this->assertIsString($entity->getId());
        $this->assertNotEmpty($entity->getId());
        $this->assertEquals(123, $entity->getMemberId());
        $this->assertEquals('test_open_gid', $entity->getOpenGid());
    }

    public function testRemove(): void
    {
        $entity = new ShareTicketLog();
        $entity->setMemberId(789);
        $entity->setShareMemberId(101112);
        $entity->setOpenGid('test_remove_gid');
        $entity->setCreateTime(new \DateTimeImmutable());

        $this->repository->save($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testFindByWithShareTimeIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareTicketLog();
        $entity->setMemberId(123);
        $entity->setShareMemberId(456);
        $entity->setOpenGid('test_share_time_null');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setShareTime(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['shareTime' => null]);

        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getShareTime());
    }

    public function testCountWithShareTimeIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareTicketLog();
        $entity->setMemberId(456);
        $entity->setShareMemberId(789);
        $entity->setOpenGid('test_count_share_time_null');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setShareTime(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['shareTime' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByValidTrue(): void
    {
        $result = $this->repository->findByValidTrue();

        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(ShareTicketLog::class, $result);
    }

    public function testFindByWithCreateTimeIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareTicketLog();
        $entity->setMemberId(123);
        $entity->setShareMemberId(456);
        $entity->setOpenGid('test_create_time_null');
        $entity->setCreateTime(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['createTime' => null]);

        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getCreateTime());
    }

    public function testCountWithCreateTimeIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareTicketLog();
        $entity->setMemberId(789);
        $entity->setShareMemberId(101112);
        $entity->setOpenGid('test_count_create_time_null');
        $entity->setCreateTime(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['createTime' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindOneByShouldRespectOrderByClause(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $time1 = new \DateTimeImmutable('2023-01-01 10:00:00');
        $time2 = new \DateTimeImmutable('2023-01-02 10:00:00');

        $entity1 = new ShareTicketLog();
        $entity1->setMemberId(601);
        $entity1->setShareMemberId(701);
        $entity1->setOpenGid('findone_order_gid_b');
        $entity1->setCreateTime($time2);

        $entity2 = new ShareTicketLog();
        $entity2->setMemberId(602);
        $entity2->setShareMemberId(701);
        $entity2->setOpenGid('findone_order_gid_a');
        $entity2->setCreateTime($time1);

        $this->repository->save($entity1);
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['shareMemberId' => 701], ['createTime' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals($time1, $result->getCreateTime());

        $result = $this->repository->findOneBy(['shareMemberId' => 701], ['createTime' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals($time2, $result->getCreateTime());

        $result = $this->repository->findOneBy(['shareMemberId' => 701], ['openGid' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals('findone_order_gid_a', $result->getOpenGid());

        $result = $this->repository->findOneBy(['shareMemberId' => 701], ['openGid' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals('findone_order_gid_b', $result->getOpenGid());
    }
}
