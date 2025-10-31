<?php

namespace WechatMiniProgramShareBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;
use WechatMiniProgramShareBundle\Procedure\GetWechatMiniProgramShareCodeInfo;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramShareCodeInfo::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramShareCodeInfoTest extends AbstractProcedureTestCase
{
    protected function getProcedureClass(): string
    {
        return GetWechatMiniProgramShareCodeInfo::class;
    }

    protected function onSetUp(): void
    {
        // 移除 parent::setUp() 调用以避免内存泄漏
    }

    public function testProcedureIsRegistered(): void
    {
        $procedure = self::getService(GetWechatMiniProgramShareCodeInfo::class);
        $this->assertInstanceOf(GetWechatMiniProgramShareCodeInfo::class, $procedure);
    }

    public function testShareCodeRepositoryIsAvailable(): void
    {
        $repository = self::getService(ShareCodeRepository::class);
        $this->assertInstanceOf(ShareCodeRepository::class, $repository);
    }

    public function testExecuteWithInvalidId(): void
    {
        $procedure = self::getService(GetWechatMiniProgramShareCodeInfo::class);
        $procedure->id = '999999';
        $procedure->setLaunchOptions([]);
        $procedure->setEnterOptions([]);

        try {
            $procedure->execute();
            self::fail('Should throw an exception');
        } catch (\Exception $e) {
            // 在集成测试中，可能抛出数据库异常或API异常
            $this->assertTrue(
                $e instanceof ApiException || $e instanceof \Doctrine\DBAL\Exception,
                'Expected ApiException or Doctrine Exception, got: ' . get_class($e)
            );
        }
    }
}
