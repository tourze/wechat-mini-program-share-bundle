<?php

namespace WechatMiniProgramShareBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;

class ShareCodeTest extends TestCase
{
    private ShareCode $shareCode;

    protected function setUp(): void
    {
        $this->shareCode = new ShareCode();
    }

    public function testGetterSetterForId(): void
    {
        // getId方法只返回属性值，不需要特殊测试
        $reflection = new \ReflectionProperty($this->shareCode, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->shareCode, 123);

        $this->assertEquals(123, $this->shareCode->getId());
    }

    public function testGetterSetterForAccount(): void
    {
        $account = $this->createMock(Account::class);
        $this->shareCode->setAccount($account);
        $this->assertSame($account, $this->shareCode->getAccount());
    }

    public function testGetterSetterForLinkUrl(): void
    {
        $linkUrl = 'https://example.com/page';
        $this->shareCode->setLinkUrl($linkUrl);
        $this->assertEquals($linkUrl, $this->shareCode->getLinkUrl());
    }

    public function testGetterSetterForImageUrl(): void
    {
        $imageUrl = 'https://example.com/image.jpg';
        $this->shareCode->setImageUrl($imageUrl);
        $this->assertEquals($imageUrl, $this->shareCode->getImageUrl());
    }

    public function testGetterSetterForEnvVersion(): void
    {
        $envVersion = EnvVersion::RELEASE;
        $this->shareCode->setEnvVersion($envVersion);
        $this->assertEquals($envVersion, $this->shareCode->getEnvVersion());
    }

    public function testGetterSetterForValid(): void
    {
        $valid = true;
        $this->shareCode->setValid($valid);
        $this->assertEquals($valid, $this->shareCode->isValid());
    }

    public function testGetterSetterForUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $this->shareCode->setUser($user);
        $this->assertSame($user, $this->shareCode->getUser());
    }

    public function testGetterSetterForSize(): void
    {
        $size = 800;
        $this->shareCode->setSize($size);
        $this->assertEquals($size, $this->shareCode->getSize());
    }

    public function testGetterSetterForCreatedFromIp(): void
    {
        $ip = '192.168.1.1';
        $this->shareCode->setCreatedFromIp($ip);
        $this->assertEquals($ip, $this->shareCode->getCreatedFromIp());
    }

    public function testGetterSetterForUpdatedFromIp(): void
    {
        $ip = '10.0.0.1';
        $this->shareCode->setUpdatedFromIp($ip);
        $this->assertEquals($ip, $this->shareCode->getUpdatedFromIp());
    }

    public function testGetterSetterForCreatedBy(): void
    {
        $createdBy = 'admin';
        $this->shareCode->setCreatedBy($createdBy);
        $this->assertEquals($createdBy, $this->shareCode->getCreatedBy());
    }

    public function testGetterSetterForUpdatedBy(): void
    {
        $updatedBy = 'editor';
        $this->shareCode->setUpdatedBy($updatedBy);
        $this->assertEquals($updatedBy, $this->shareCode->getUpdatedBy());
    }

    public function testGetterSetterForCreateTime(): void
    {
        $createTime = new \DateTime();
        $reflection = new \ReflectionProperty($this->shareCode, 'createTime');
        $reflection->setAccessible(true);
        $reflection->setValue($this->shareCode, $createTime);
        $this->assertSame($createTime, $this->shareCode->getCreateTime());
    }

    public function testGetterSetterForUpdateTime(): void
    {
        $updateTime = new \DateTime();
        $this->shareCode->setUpdateTime($updateTime);
        $this->assertSame($updateTime, $this->shareCode->getUpdateTime());
    }
}