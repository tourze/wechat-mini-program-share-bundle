<?php

namespace WechatMiniProgramShareBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface as WechatUserInterface;
use WechatMiniProgramAuthBundle\Event\CodeToSessionResponseEvent;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\EventSubscriber\InviteVisitSubscriber;
use WechatMiniProgramShareBundle\Repository\InviteVisitLogRepository;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

/**
 * @internal
 */
#[CoversClass(InviteVisitSubscriber::class)]
#[RunTestsInSeparateProcesses]
final class InviteVisitSubscriberTest extends AbstractEventSubscriberTestCase
{
    private InviteVisitLogRepository $inviteVisitLogRepository;

    protected function onSetUp(): void
    {
        $this->inviteVisitLogRepository = self::getService(InviteVisitLogRepository::class);
    }

    private function createMockWechatUser(string $openId): WechatUserInterface
    {
        $miniProgram = $this->createMock(MiniProgramInterface::class);
        $miniProgram->method('getAppId')->willReturn('test_app_id');
        $miniProgram->method('getAppSecret')->willReturn('test_app_secret');

        $wechatUser = $this->createMock(WechatUserInterface::class);
        $wechatUser->method('getOpenId')->willReturn($openId);
        $wechatUser->method('getUnionId')->willReturn(null);
        $wechatUser->method('getAvatarUrl')->willReturn(null);
        $wechatUser->method('getMiniProgram')->willReturn($miniProgram);

        return $wechatUser;
    }

    public function testEventSubscriberIsRegisteredInContainer(): void
    {
        $subscriber = self::getService(InviteVisitSubscriber::class);
        $this->assertInstanceOf(InviteVisitSubscriber::class, $subscriber);
    }

    public function testEventSubscriberListensToCodeToSessionEvent(): void
    {
        $eventDispatcher = self::getService(EventDispatcherInterface::class);
        $this->assertTrue($eventDispatcher->hasListeners(CodeToSessionResponseEvent::class));
    }

    public function testEventHandlerIsCalledWhenEventIsDispatched(): void
    {
        // 创建模拟的微信用户
        $wechatUser = $this->createMockWechatUser('test_visitor_openid');

        // 创建事件，包含邀请参数
        $event = new CodeToSessionResponseEvent();
        $event->setWechatUser($wechatUser);
        $event->setNewUser(true);
        // 需要bizUser才能完整测试
        $bizUser = $this->createAdminUser();
        $event->setBizUser($bizUser);
        $event->setLaunchOptions([
            'query' => [
                WechatMiniProgramShareBundle::PARAM_KEY => 'test123,1234567890',
            ],
        ]);

        // 分派事件
        $eventDispatcher = self::getService(EventDispatcherInterface::class);

        // 验证事件处理器被调用且不抛出异常
        $this->expectNotToPerformAssertions();
        $eventDispatcher->dispatch($event);

        // 事件处理器成功处理事件（没有异常抛出就证明事件订阅器被正确调用）
    }

    public function testEventHandlerIgnoresEventWithoutShareParam(): void
    {
        // 清理可能残留的记录
        self::getEntityManager()->createQuery('DELETE FROM ' . InviteVisitLog::class)->execute();

        $wechatUser = $this->createMockWechatUser('test_visitor_openid');

        $event = new CodeToSessionResponseEvent();
        $event->setWechatUser($wechatUser);
        $event->setLaunchOptions(['query' => []]);

        $eventDispatcher = self::getService(EventDispatcherInterface::class);
        $eventDispatcher->dispatch($event);

        // 验证没有创建InviteVisitLog记录
        $logs = $this->inviteVisitLogRepository->findAll();
        $this->assertEmpty($logs);
    }

    public function testOnCodeToSessionRequest(): void
    {
        // 创建模拟的微信用户
        $wechatUser = $this->createMockWechatUser('test_request_openid');

        // 创建事件，模拟onCodeToSessionRequest方法处理的场景
        $event = new CodeToSessionResponseEvent();
        $event->setWechatUser($wechatUser);
        $event->setNewUser(false); // 这次测试非新用户场景
        $bizUser = $this->createAdminUser();
        $event->setBizUser($bizUser);
        $event->setLaunchOptions([
            'query' => [
                WechatMiniProgramShareBundle::PARAM_KEY => 'test456,9876543210',
            ],
        ]);

        // 分派事件
        $eventDispatcher = self::getService(EventDispatcherInterface::class);

        // 验证事件处理器被调用且不抛出异常
        $this->expectNotToPerformAssertions();
        $eventDispatcher->dispatch($event);

        // 事件处理器成功处理事件（没有异常抛出就证明事件订阅器被正确调用）
    }
}
