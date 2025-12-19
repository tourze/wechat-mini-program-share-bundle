<?php

namespace WechatMiniProgramShareBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Model\JsonRpcParams;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramPageShareConfig;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramPageShareConfig::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramPageShareConfigTest extends AbstractProcedureTestCase
{
    protected function getProcedureClass(): string
    {
        return GetWechatMiniProgramPageShareConfig::class;
    }

    protected function onSetUp(): void
    {
        // 移除 parent::setUp() 调用以避免内存泄漏
    }

    public function testProcedureIsRegistered(): void
    {
        $procedure = self::getService(GetWechatMiniProgramPageShareConfig::class);
        $this->assertInstanceOf(GetWechatMiniProgramPageShareConfig::class, $procedure);
    }

    public function testGetCacheKey(): void
    {
        $procedure = self::getService(GetWechatMiniProgramPageShareConfig::class);
        $request = new JsonRpcRequest();
        $request->setParams(new JsonRpcParams());
        $key = $procedure->getCacheKey($request);
        $this->assertIsString($key);
    }

    public function testGetCacheDuration(): void
    {
        $procedure = self::getService(GetWechatMiniProgramPageShareConfig::class);
        $request = new JsonRpcRequest();
        $request->setParams(new JsonRpcParams());
        $duration = $procedure->getCacheDuration($request);
        $this->assertIsInt($duration);
        $this->assertGreaterThan(0, $duration);
    }

    public function testExecute(): void
    {
        $procedure = self::getService(GetWechatMiniProgramPageShareConfig::class);

        // 创建参数对象
        $param = new \WechatMiniProgramShareBundle\Param\GetWechatMiniProgramPageShareConfigParam();
        $param->path = '/pages/index/index';
        $param->params = [];
        $param->config = [
            'path' => '/pages/index/index',
            'title' => 'Test Share Config',
        ];

        $result = $procedure->execute($param);

        // 验证返回结果的基本结构
        $this->assertInstanceOf(\Tourze\JsonRPC\Core\Result\ArrayResult::class, $result);
        $this->assertIsArray($result->data);
        $this->assertArrayHasKey('path', $result->data);
        $this->assertArrayHasKey('title', $result->data);
    }
}
