<?php

namespace WechatMiniProgramShareBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

class ShareTicketLogTest extends TestCase
{
    public function testGetterSetter(): void
    {
        $log = new ShareTicketLog();
        $log->setOpenGid('gid456');
        $log->setMemberId(123);
        $log->setShareMemberId(456);
        $log->setShareTime(new \DateTimeImmutable('2023-01-01 00:00:00'));
        $log->setCreateTime(new \DateTimeImmutable('2023-01-02 00:00:00'));
        
        $this->assertEquals('gid456', $log->getOpenGid());
        $this->assertEquals(123, $log->getMemberId());
        $this->assertEquals(456, $log->getShareMemberId());
        $this->assertInstanceOf(\DateTimeInterface::class, $log->getShareTime());
        $this->assertInstanceOf(\DateTimeInterface::class, $log->getCreateTime());
    }

    public function testSettersWithEdgeCases(): void
    {
        $log = new ShareTicketLog();
        $log->setOpenGid('');
        $log->setMemberId(0);
        $log->setShareMemberId(0);
        $currentDate = new \DateTimeImmutable();
        $log->setShareTime($currentDate);
        $log->setCreateTime(null);
        
        $this->assertEquals('', $log->getOpenGid());
        $this->assertEquals(0, $log->getMemberId());
        $this->assertEquals(0, $log->getShareMemberId());
        $this->assertSame($currentDate, $log->getShareTime());
        $this->assertNull($log->getCreateTime());
    }
}