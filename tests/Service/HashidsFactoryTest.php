<?php

namespace WechatMiniProgramShareBundle\Tests\Service;

use Hashids\Hashids;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\Service\HashidsFactory;

/**
 * @internal
 */
#[CoversClass(HashidsFactory::class)]
final class HashidsFactoryTest extends TestCase
{
    public function testCreateHashids(): void
    {
        $_ENV['HASHID_SALT'] = 'test-salt';

        $hashids = HashidsFactory::createHashids();

        /*
         * 使用 Hashids 具体类的原因：
         * 1. 这是针对 HashidsFactory 的单元测试，需要验证创建的 Hashids 实例类型
         * 2. Hashids 是第三方库的具体实现，不是接口
         * 3. 需要验证 Factory 正确创建了 Hashids 实例
         */
        // Test encoding and decoding works with salt
        $encoded = $hashids->encode(1, 2, 3);
        $this->assertNotEmpty($encoded);

        $decoded = $hashids->decode($encoded);
        $this->assertEquals([1, 2, 3], $decoded);
    }

    public function testCreateHashidsWithoutSalt(): void
    {
        unset($_ENV['HASHID_SALT']);

        $hashids = HashidsFactory::createHashids();

        /*
         * 使用 Hashids 具体类的原因：
         * 1. 这是针对 HashidsFactory 的单元测试，需要验证创建的 Hashids 实例类型
         * 2. Hashids 是第三方库的具体实现，不是接口
         * 3. 需要验证 Factory 在没有 salt 的情况下也能正确创建 Hashids 实例
         */
        // Test basic functionality without salt
        $encoded = $hashids->encode(123);
        $this->assertNotEmpty($encoded);
        $this->assertEquals([123], $hashids->decode($encoded));
    }
}
