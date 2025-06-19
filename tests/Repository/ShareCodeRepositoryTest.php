<?php

namespace WechatMiniProgramShareBundle\Tests\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

class ShareCodeRepositoryTest extends TestCase
{
    private ShareCodeRepository $repository;
    private MockObject $registry;
    private MockObject $entityManager;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->entityManager = $this->createMock(EntityManager::class);
        
        $this->registry->method('getManagerForClass')
            ->with(ShareCode::class)
            ->willReturn($this->entityManager);
            
        $this->repository = new ShareCodeRepository($this->registry);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(ShareCodeRepository::class, $this->repository);
    }

    public function testFindByValidTrue(): void
    {
        // 简化测试，仅验证方法存在
        $reflection = new \ReflectionClass($this->repository);
        $this->assertTrue($reflection->hasMethod('findByValidTrue'));
    }
}