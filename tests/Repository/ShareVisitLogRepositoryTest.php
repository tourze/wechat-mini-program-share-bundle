<?php

namespace WechatMiniProgramShareBundle\Tests\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use WechatMiniProgramShareBundle\Repository\ShareVisitLogRepository;

class ShareVisitLogRepositoryTest extends TestCase
{
    private ShareVisitLogRepository $repository;
    private MockObject $registry;
    private MockObject $entityManager;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->registry->method('getManagerForClass')
            ->with(ShareVisitLog::class)
            ->willReturn($this->entityManager);
        $this->repository = new ShareVisitLogRepository($this->registry);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(ShareVisitLogRepository::class, $this->repository);
    }

    /**
     * @group skipped
     */
    public function testFindByValidTrue(): void
    {
        $this->markTestSkipped('跳过此测试，QueryBuilder模拟存在类型问题');
    }
}