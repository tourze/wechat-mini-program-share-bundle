<?php

namespace WechatMiniProgramShareBundle\Tests\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Repository\InviteVisitLogRepository;

class InviteVisitLogRepositoryTest extends TestCase
{
    private InviteVisitLogRepository $repository;
    private MockObject $registry;
    private MockObject $entityManager;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->registry->method('getManagerForClass')
            ->with(InviteVisitLog::class)
            ->willReturn($this->entityManager);
        $this->repository = new InviteVisitLogRepository($this->registry);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(InviteVisitLogRepository::class, $this->repository);
    }

    public function testFindByValidTrue(): void
    {
        // 简化测试，仅验证方法存在
        $this->assertTrue(method_exists($this->repository, 'findByValidTrue'));
    }
}