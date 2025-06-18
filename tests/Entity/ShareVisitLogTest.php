<?php

namespace WechatMiniProgramShareBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Entity\ShareVisitLog;

class ShareVisitLogTest extends TestCase
{
    public function testGetterSetter(): void
    {
        $log = new ShareVisitLog();
        
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