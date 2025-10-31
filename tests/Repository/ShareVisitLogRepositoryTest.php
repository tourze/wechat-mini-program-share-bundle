<?php

namespace WechatMiniProgramShareBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;
use WechatMiniProgramShareBundle\Repository\ShareVisitLogRepository;

/**
 * @internal
 */
#[CoversClass(ShareVisitLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShareVisitLogRepositoryTest extends AbstractRepositoryTestCase
{
    private ShareVisitLogRepository $repository;

    private ShareCodeRepository $shareCodeRepository;

    protected function createNewEntity(): object
    {
        $entity = new ShareVisitLog();

        // ShareVisitLog 需要一个 ShareCode
        $shareCode = $this->createTestShareCode();
        $entity->setCode($shareCode);
        $entity->setCreateTime(new \DateTimeImmutable());

        return $entity;
    }

    /**
     * @return ShareVisitLogRepository
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ShareVisitLogRepository::class);
        $this->shareCodeRepository = self::getService(ShareCodeRepository::class);
    }

    private function createTestShareCode(): ShareCode
    {
        $shareCode = new ShareCode();
        $shareCode->setLinkUrl('https://example.com/test');
        $this->shareCodeRepository->save($shareCode);

        return $shareCode;
    }

    public function testSave(): void
    {
        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.1');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'data']);

        $this->repository->save($entity);

        $this->assertIsString($entity->getId());
        $this->assertNotEmpty($entity->getId());
        $this->assertEquals('192.168.1.1', $entity->getCreatedFromIp());
    }

    public function testRemove(): void
    {
        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.2');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'remove']);

        $this->repository->save($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testFindByWithCodeAssociation(): void
    {
        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.3');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'code']);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['code' => $shareCode]);

        $this->assertGreaterThan(0, \count($result));
        $this->assertNotNull($result[0]->getCode());
        $this->assertEquals($shareCode->getId(), $result[0]->getCode()->getId());
    }

    public function testCountWithCodeAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.4');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'count_code']);

        $this->repository->save($entity);

        $count = $this->repository->count(['code' => $shareCode]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithUserAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.5');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'user']);
        $entity->setUser(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['user' => null]);

        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getUser());
    }

    public function testCountWithUserAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.6');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'count_user']);
        $entity->setUser(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['user' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByValidTrue(): void
    {
        $result = $this->repository->findByValidTrue();

        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(ShareVisitLog::class, $result);
    }

    public function testFindByWithCreateTimeIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.time');
        $entity->setCreateTime(null);
        $entity->setResponse(['test' => 'time_null']);

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

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.count-time');
        $entity->setCreateTime(null);
        $entity->setResponse(['test' => 'count_time_null']);

        $this->repository->save($entity);

        $count = $this->repository->count(['createTime' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithLaunchOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.launch');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'launch_null']);
        $entity->setLaunchOptions(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['launchOptions' => null]);

        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getLaunchOptions());
    }

    public function testCountWithLaunchOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.count-launch');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'count_launch_null']);
        $entity->setLaunchOptions(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['launchOptions' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithEnterOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.enter');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'enter_null']);
        $entity->setEnterOptions(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['enterOptions' => null]);

        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getEnterOptions());
    }

    public function testCountWithEnterOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $entity = new ShareVisitLog();
        $entity->setCode($shareCode);
        $entity->setCreatedFromIp('192.168.1.count-enter');
        $entity->setCreateTime(new \DateTimeImmutable());
        $entity->setResponse(['test' => 'count_enter_null']);
        $entity->setEnterOptions(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['enterOptions' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindOneByShouldRespectOrderByClause(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $shareCode = $this->createTestShareCode();
        $time1 = new \DateTimeImmutable('2023-01-01 10:00:00');
        $time2 = new \DateTimeImmutable('2023-01-02 10:00:00');

        $entity1 = new ShareVisitLog();
        $entity1->setCode($shareCode);
        $entity1->setCreatedFromIp('192.168.1.findone-b');
        $entity1->setCreateTime($time2);
        $entity1->setResponse(['test' => 'findone_order_b']);

        $entity2 = new ShareVisitLog();
        $entity2->setCode($shareCode);
        $entity2->setCreatedFromIp('192.168.1.findone-a');
        $entity2->setCreateTime($time1);
        $entity2->setResponse(['test' => 'findone_order_a']);

        $this->repository->save($entity1);
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['code' => $shareCode], ['createTime' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals($time1, $result->getCreateTime());

        $result = $this->repository->findOneBy(['code' => $shareCode], ['createTime' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals($time2, $result->getCreateTime());

        $result = $this->repository->findOneBy(['code' => $shareCode], ['createdFromIp' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals('192.168.1.findone-a', $result->getCreatedFromIp());

        $result = $this->repository->findOneBy(['code' => $shareCode], ['createdFromIp' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals('192.168.1.findone-b', $result->getCreatedFromIp());
    }
}
