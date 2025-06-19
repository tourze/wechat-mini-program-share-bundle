<?php

namespace WechatMiniProgramShareBundle\Tests\Procedure;

use Carbon\CarbonImmutable;
use Hashids\Hashids;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\JsonRPC\Core\Model\JsonRpcParams;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramPageShareConfig;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

class GetWechatMiniProgramPageShareConfigTest extends TestCase
{
    private GetWechatMiniProgramPageShareConfig $procedure;
    private MockObject $hashids;
    private MockObject $security;

    protected function setUp(): void
    {
        $this->hashids = $this->createMock(Hashids::class);
        $this->security = $this->createMock(Security::class);
        $this->procedure = new GetWechatMiniProgramPageShareConfig(
            $this->hashids,
            $this->security
        );
    }

    public function testExecuteWithoutConfig(): void
    {
        $this->procedure->config = [];
        $result = $this->procedure->execute();
        $this->assertSame([], $result);
    }

    public function testExecuteWithoutUser(): void
    {
        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $this->procedure->config = [
            'path' => '/pages/home',
        ];

        $result = $this->procedure->execute();
        $this->assertSame(['path' => '/pages/home'], $result);
    }

    public function testExecuteWithLoggedInUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())
            ->method('getUserIdentifier')
            ->willReturn('user123');

        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        CarbonImmutable::setTestNow('2023-01-01 12:00:00');
        $timestamp = CarbonImmutable::now()->getTimestamp();

        $this->hashids->expects($this->once())
            ->method('encode')
            ->with('user123', $timestamp)
            ->willReturn('abc123');

        $this->procedure->config = [
            'path' => '/pages/detail?id=456',
        ];

        $result = $this->procedure->execute();

        $expectedPath = '/pages/detail?id=456&' . WechatMiniProgramShareBundle::PARAM_KEY . '=abc123';
        $this->assertEquals(['path' => $expectedPath], $result);

        CarbonImmutable::setTestNow(null);
    }

    public function testGetCacheKeyWithLoggedInUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $request = new JsonRpcRequest();
        $key = $this->procedure->getCacheKey($request);
        $this->assertSame('', $key);
    }

    public function testGetCacheKeyWithoutUser(): void
    {
        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $request = new JsonRpcRequest();
        $params = new JsonRpcParams();
        $params->set('test', 'value');
        $request->setParams($params);
        
        $key = $this->procedure->getCacheKey($request);
        $this->assertNotEmpty($key);
    }

    public function testGetCacheDuration(): void
    {
        $request = new JsonRpcRequest();
        $duration = $this->procedure->getCacheDuration($request);
        $this->assertSame(60, $duration);
    }

    public function testGetCacheTags(): void
    {
        $request = new JsonRpcRequest();
        $tags = iterator_to_array($this->procedure->getCacheTags($request));
        $this->assertSame([null], $tags);
    }
}