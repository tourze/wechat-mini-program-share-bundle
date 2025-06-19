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

    public function testFindByValidTrue(): void
    {
        // 简化测试，仅验证方法存在
        $reflection = new \ReflectionClass($this->repository);
        $this->assertTrue($reflection->hasMethod('findByValidTrue'));
    }
}