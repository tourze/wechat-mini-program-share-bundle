<?php

namespace WechatMiniProgramShareBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

/**
 * @internal
 */
#[CoversClass(ShareVisitLog::class)]
final class ShareVisitLogTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ShareVisitLog();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'envVersion' => ['envVersion', EnvVersion::RELEASE],
            'launchOptions' => ['launchOptions', ['key' => 'value']],
            'enterOptions' => ['enterOptions', ['key' => 'value']],
            'response' => ['response', ['success' => true]],
            'createTime' => ['createTime', new \DateTimeImmutable()],
            'createdFromIp' => ['createdFromIp', '192.168.1.1'],
        ];
    }

    public function testGetterSetter(): void
    {
        $log = new ShareVisitLog();

        /**
         * 使用 ShareCode 具体类的原因：
         * 1. 这是针对 ShareVisitLog Entity 的单元测试，需要验证与 ShareCode 类的关联关系
         * 2. ShareCode 类包含了 Doctrine ORM 特有的功能，需要验证完整的实体关联
         * 3. ShareVisitLog 实体类型声明明确要求 ShareCode 类型，不是通用接口
         */
        $shareCode = $this->createMock(ShareCode::class);
        $log->setCode($shareCode);
        $log->setEnvVersion(EnvVersion::RELEASE);

        $user = $this->createMock(UserInterface::class);
        $log->setUser($user);

        $log->setLaunchOptions(['foo' => 'bar']);
        $log->setEnterOptions(['bar' => 'baz']);
        $log->setResponse(['ok' => true]);
        $log->setCreateTime(new \DateTimeImmutable('2023-01-01 00:00:00'));

        $this->assertSame($shareCode, $log->getCode());
        $this->assertEquals(EnvVersion::RELEASE, $log->getEnvVersion());
        $this->assertSame($user, $log->getUser());
        $this->assertEquals(['foo' => 'bar'], $log->getLaunchOptions());
        $this->assertEquals(['bar' => 'baz'], $log->getEnterOptions());
        $this->assertEquals(['ok' => true], $log->getResponse());
        $this->assertInstanceOf(\DateTimeInterface::class, $log->getCreateTime());
    }

    public function testSettersWithEdgeCases(): void
    {
        $log = new ShareVisitLog();
        $log->setCode(null);
        $log->setEnvVersion(null);
        $log->setUser(null);
        $log->setLaunchOptions([]);
        $log->setEnterOptions([]);
        $log->setResponse([]);
        $log->setCreateTime(null);
        $log->setCreatedFromIp(null);

        $this->assertNull($log->getCode());
        $this->assertNull($log->getEnvVersion());
        $this->assertNull($log->getUser());
        $this->assertEquals([], $log->getLaunchOptions());
        $this->assertEquals([], $log->getEnterOptions());
        $this->assertEquals([], $log->getResponse());
        $this->assertNull($log->getCreateTime());
        $this->assertNull($log->getCreatedFromIp());
    }
}
