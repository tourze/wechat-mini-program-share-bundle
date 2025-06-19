<?php

namespace WechatMiniProgramShareBundle\Tests\Procedure;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use Tourze\JsonRPC\Core\Exception\ApiException;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramShareCodeInfo;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

class GetWechatMiniProgramShareCodeInfoTest extends TestCase
{
    private GetWechatMiniProgramShareCodeInfo $procedure;
    private MockObject $codeRepository;
    private MockObject $doctrineService;
    private MockObject $security;
    private MockObject $logger;

    protected function setUp(): void
    {
        $this->codeRepository = $this->createMock(ShareCodeRepository::class);
        $this->doctrineService = $this->createMock(DoctrineService::class);
        $this->security = $this->createMock(Security::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->procedure = new GetWechatMiniProgramShareCodeInfo(
            $this->codeRepository,
            $this->doctrineService,
            $this->security,
            $this->logger
        );
        
        // 设置必要属性
        $this->procedure->id = '1';
        $this->procedure->launchOptions = [];
        $this->procedure->enterOptions = [];
    }

    public function testExecuteThrowsExceptionWhenCodeNotFound(): void
    {
        $this->codeRepository->method('find')->willReturn(null);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到分享码');

        $this->procedure->execute();
    }

    public function testExecuteThrowsExceptionWhenCodeIsInvalid(): void
    {
        $shareCode = $this->createMock(ShareCode::class);
        $shareCode->method('isValid')->willReturn(false);

        $this->codeRepository->method('find')->willReturn($shareCode);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('分享码已无效');

        $this->procedure->execute();
    }

    public function testExecuteReturnsRedirectForValidCode(): void
    {
        // 创建有效的分享码模拟对象
        $shareCode = $this->createMock(ShareCode::class);
        $shareCode->method('isValid')->willReturn(true);
        $shareCode->method('getLinkUrl')->willReturn('pages/test/test');
        $shareCode->method('getEnvVersion')->willReturn(EnvVersion::RELEASE);

        $this->codeRepository->method('find')->willReturn($shareCode);
        
        // 模拟用户已登录
        $user = $this->createMock(UserInterface::class);
        $this->security->method('getUser')->willReturn($user);
        
        // 模拟异步插入操作
        $this->doctrineService->expects($this->once())
            ->method('asyncInsert')
            ->with($this->isInstanceOf(ShareVisitLog::class));

        $result = $this->procedure->execute();
        
        // 验证返回值包含正确的重定向信息
        $this->assertArrayHasKey('__redirectTo', $result);
        $this->assertIsArray($result['__redirectTo']);
        $this->assertArrayHasKey('url', $result['__redirectTo']);
        $this->assertEquals('/pages/test/test', $result['__redirectTo']['url']);
    }

    public function testExecuteHandlesTabPageRedirection(): void
    {
        // 创建有效的分享码模拟对象，链接指向默认首页
        $shareCode = $this->createMock(ShareCode::class);
        $shareCode->method('isValid')->willReturn(true);
        $shareCode->method('getLinkUrl')->willReturn('pages/index/index');
        $shareCode->method('getEnvVersion')->willReturn(EnvVersion::RELEASE);

        $this->codeRepository->method('find')->willReturn($shareCode);
        
        // 模拟异步插入操作
        $this->doctrineService->expects($this->once())
            ->method('asyncInsert')
            ->with($this->isInstanceOf(ShareVisitLog::class));

        $result = $this->procedure->execute();
        
        // 验证返回值包含正确的重定向信息 (对于Tab页使用reLaunch)
        $this->assertArrayHasKey('__reLaunch', $result);
        $this->assertIsArray($result['__reLaunch']);
        $this->assertArrayHasKey('url', $result['__reLaunch']);
        $this->assertEquals('/pages/index/index', $result['__reLaunch']['url']);
    }
}
