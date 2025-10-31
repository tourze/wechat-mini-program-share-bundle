<?php

namespace WechatMiniProgramShareBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramShareBundle\Entity\ShareTicketLog;

/**
 * @internal
 */
#[CoversClass(ShareTicketLog::class)]
final class ShareTicketLogTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ShareTicketLog();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'openGid' => ['openGid', 'test_group_id'],
            'memberId' => ['memberId', 123],
            'shareMemberId' => ['shareMemberId', 456],
            'shareTime' => ['shareTime', new \DateTimeImmutable()],
            'createTime' => ['createTime', new \DateTimeImmutable()],
        ];
    }

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
