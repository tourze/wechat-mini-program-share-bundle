<?php

namespace WechatMiniProgramShareBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;

/**
 * @internal
 */
#[CoversClass(ShareCode::class)]
final class ShareCodeTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ShareCode();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'linkUrl' => ['linkUrl', 'https://example.com/test'],
            'imageUrl' => ['imageUrl', 'https://example.com/image.jpg'],
            'envVersion' => ['envVersion', EnvVersion::RELEASE],
            'valid' => ['valid', true],
            'size' => ['size', 800],
            'createdFromIp' => ['createdFromIp', '192.168.1.1'],
            'updatedFromIp' => ['updatedFromIp', '10.0.0.1'],
            'createdBy' => ['createdBy', 'admin'],
            'updatedBy' => ['updatedBy', 'editor'],
        ];
    }

    public function testGetterSetterForId(): void
    {
        // getId方法只返回属性值，不需要特殊测试
        $shareCode = new ShareCode();
        $reflection = new \ReflectionProperty($shareCode, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($shareCode, 123);

        $this->assertEquals(123, $shareCode->getId());
    }

    public function testGetterSetterForAccount(): void
    {
        /**
         * 使用 Account 具体类的原因：
         * 1. 这是针对 ShareCode Entity 的单元测试，需要验证与 Account 类的关联关系
         * 2. Account 类包含了 Doctrine ORM 特有的功能，需要验证完整的实体关联
         * 3. ShareCode 实体类型声明明确要求 Account 类型，不是通用接口
         */
        $shareCode = new ShareCode();
        $account = $this->createMock(Account::class);
        $shareCode->setAccount($account);
        $this->assertSame($account, $shareCode->getAccount());
    }

    public function testGetterSetterForLinkUrl(): void
    {
        $shareCode = new ShareCode();
        $linkUrl = 'https://example.com/page';
        $shareCode->setLinkUrl($linkUrl);
        $this->assertEquals($linkUrl, $shareCode->getLinkUrl());
    }

    public function testGetterSetterForImageUrl(): void
    {
        $shareCode = new ShareCode();
        $imageUrl = 'https://example.com/image.jpg';
        $shareCode->setImageUrl($imageUrl);
        $this->assertEquals($imageUrl, $shareCode->getImageUrl());
    }

    public function testGetterSetterForEnvVersion(): void
    {
        $shareCode = new ShareCode();
        $envVersion = EnvVersion::RELEASE;
        $shareCode->setEnvVersion($envVersion);
        $this->assertEquals($envVersion, $shareCode->getEnvVersion());
    }

    public function testGetterSetterForValid(): void
    {
        $shareCode = new ShareCode();
        $valid = true;
        $shareCode->setValid($valid);
        $this->assertEquals($valid, $shareCode->isValid());
    }

    public function testGetterSetterForUser(): void
    {
        $shareCode = new ShareCode();
        $user = $this->createMock(UserInterface::class);
        $shareCode->setUser($user);
        $this->assertSame($user, $shareCode->getUser());
    }

    public function testGetterSetterForSize(): void
    {
        $shareCode = new ShareCode();
        $size = 800;
        $shareCode->setSize($size);
        $this->assertEquals($size, $shareCode->getSize());
    }

    public function testGetterSetterForCreatedFromIp(): void
    {
        $shareCode = new ShareCode();
        $ip = '192.168.1.1';
        $shareCode->setCreatedFromIp($ip);
        $this->assertEquals($ip, $shareCode->getCreatedFromIp());
    }

    public function testGetterSetterForUpdatedFromIp(): void
    {
        $shareCode = new ShareCode();
        $ip = '10.0.0.1';
        $shareCode->setUpdatedFromIp($ip);
        $this->assertEquals($ip, $shareCode->getUpdatedFromIp());
    }

    public function testGetterSetterForCreatedBy(): void
    {
        $shareCode = new ShareCode();
        $createdBy = 'admin';
        $shareCode->setCreatedBy($createdBy);
        $this->assertEquals($createdBy, $shareCode->getCreatedBy());
    }

    public function testGetterSetterForUpdatedBy(): void
    {
        $shareCode = new ShareCode();
        $updatedBy = 'editor';
        $shareCode->setUpdatedBy($updatedBy);
        $this->assertEquals($updatedBy, $shareCode->getUpdatedBy());
    }

    public function testGetterSetterForCreateTime(): void
    {
        $shareCode = new ShareCode();
        $createTime = new \DateTimeImmutable();
        $reflection = new \ReflectionProperty($shareCode, 'createTime');
        $reflection->setAccessible(true);
        $reflection->setValue($shareCode, $createTime);
        $this->assertSame($createTime, $shareCode->getCreateTime());
    }

    public function testGetterSetterForUpdateTime(): void
    {
        $shareCode = new ShareCode();
        $updateTime = new \DateTimeImmutable();
        $shareCode->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $shareCode->getUpdateTime());
    }
}
