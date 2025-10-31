<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;

/**
 * 微信小程序分享模块测试基类。
 *
 * @internal
 */
#[CoversClass(TestCase::class)]
abstract class AbstractWechatMiniProgramShareTest extends TestCase
{
    protected function createAccountMock(): Account
    {
        $account = $this->createMock(Account::class);
        $account->method('getId')
            ->willReturn(1)
        ;
        $account->method('getAppId')
            ->willReturn('test_appid')
        ;
        $account->method('getAppSecret')
            ->willReturn('test_secret')
        ;

        return $account;
    }

    protected function createClientMock(): Client
    {
        return $this->createMock(Client::class);
    }

    protected function createTestShareUrl(): string
    {
        return 'pages/share?code=test123';
    }

    protected function createTestInviteCode(): string
    {
        return 'invite_123456';
    }

    protected function createTestUserId(): int
    {
        return 12345;
    }

    protected function createTestHashId(): string
    {
        return 'abc123def';
    }

    protected function createTestIpAddress(): string
    {
        return '192.168.1.100';
    }

    protected function createTestUserAgent(): string
    {
        return 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15';
    }

    protected function createTestScene(): int
    {
        return 1001;
    }

    protected function createTestTimestamp(): int
    {
        return 1640995200;
    }

    /**
     * @param array<string> $expectedKeys
     * @param array<string, mixed> $array
     */
    protected function assertArrayHasKeys(array $expectedKeys, array $array, string $message = ''): void
    {
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $array, '' !== $message ? $message : "Array should have key '{$key}'");
        }
    }

    protected function assertObjectHasPropertyValue(object $object, string $property, mixed $expectedValue, string $message = ''): void
    {
        $this->assertTrue(property_exists($object, $property), '' !== $message ? $message : "Object should have property '{$property}'");
        $reflection = new \ReflectionObject($object);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);
        $this->assertEquals($expectedValue, $prop->getValue($object), '' !== $message ? $message : "Property '{$property}' should equal expected value");
    }
}
