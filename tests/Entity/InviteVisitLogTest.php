<?php

namespace WechatMiniProgramShareBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;

class InviteVisitLogTest extends TestCase
{
    public function testGetterSetter(): void
    {
        $log = new InviteVisitLog();
        $log->setShareOpenId('share_openid');
        $log->setVisitOpenId('visit_openid');
        $log->setVisitPath('/pages/index');
        $log->setNewUser(true);
        $log->setShareTime(new \DateTimeImmutable('2023-01-01 00:00:00'));
        $log->setVisitTime(new \DateTimeImmutable('2023-01-02 00:00:00'));
        $log->setLaunchOptions(['foo' => 'bar']);
        $log->setEnterOptions(['bar' => 'baz']);
        
        $this->assertEquals('share_openid', $log->getShareOpenId());
        $this->assertEquals('visit_openid', $log->getVisitOpenId());
        $this->assertEquals('/pages/index', $log->getVisitPath());
        $this->assertTrue($log->isNewUser());
        $this->assertInstanceOf(\DateTimeInterface::class, $log->getShareTime());
        $this->assertInstanceOf(\DateTimeInterface::class, $log->getVisitTime());
        $this->assertEquals(['foo' => 'bar'], $log->getLaunchOptions());
        $this->assertEquals(['bar' => 'baz'], $log->getEnterOptions());
    }

    public function testSettersWithEdgeCases(): void
    {
        $log = new InviteVisitLog();
        $log->setShareOpenId('');
        $log->setVisitOpenId('');
        $log->setVisitPath('');
        $log->setNewUser(false);
        $log->setContext(null);
        $log->setRegistered(false);
        $log->setLaunchOptions([]);
        $log->setEnterOptions([]);
        
        $this->assertEquals('', $log->getShareOpenId());
        $this->assertEquals('', $log->getVisitOpenId());
        $this->assertEquals('', $log->getVisitPath());
        $this->assertFalse($log->isNewUser());
        $this->assertNull($log->getContext());
        $this->assertFalse($log->isRegistered());
        $this->assertEquals([], $log->getLaunchOptions());
        $this->assertEquals([], $log->getEnterOptions());
    }
}