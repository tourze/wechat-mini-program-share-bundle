<?php

namespace WechatMiniProgramShareBundle\Tests\EventSubscriber;

use Hashids\Hashids;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Link\LinkInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;
use WechatMiniProgramAuthBundle\Entity\User as WechatUser;
use WechatMiniProgramAuthBundle\Event\CodeToSessionResponseEvent;
use WechatMiniProgramAuthBundle\Repository\UserRepository;
use WechatMiniProgramBundle\Service\LaunchOptionHelper;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Event\InviteUserEvent;
use WechatMiniProgramShareBundle\EventSubscriber\InviteVisitSubscriber;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

class InviteVisitSubscriberTest extends TestCase
{
    private InviteVisitSubscriber $subscriber;
    private MockObject $doctrineService;
    private MockObject $userLoader;
    private MockObject $userRepository;
    private MockObject $logger;
    private MockObject $hashids;
    private MockObject $eventDispatcher;
    private MockObject $launchOptionHelper;

    protected function setUp(): void
    {
        $this->doctrineService = $this->createMock(AsyncInsertService::class);
        $this->userLoader = $this->createMock(UserLoaderInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->hashids = $this->createMock(Hashids::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->launchOptionHelper = $this->createMock(LaunchOptionHelper::class);

        $this->subscriber = new InviteVisitSubscriber(
            $this->doctrineService,
            $this->userLoader,
            $this->userRepository,
            $this->logger,
            $this->hashids,
            $this->eventDispatcher,
            $this->launchOptionHelper
        );
    }

    public function testOnCodeToSessionRequestWithNoParameters(): void
    {
        $event = $this->createMock(CodeToSessionResponseEvent::class);
        
        $launchOption = $this->createMock(LinkInterface::class);
        $launchOption->expects($this->once())
            ->method('getAttributes')
            ->willReturn([]);

        $this->launchOptionHelper->expects($this->once())
            ->method('parseEvent')
            ->with($event)
            ->willReturn($launchOption);

        $this->logger->expects($this->once())
            ->method('warning')
            ->with('找不到必要的参数，不处理', ['query' => []]);

        $this->subscriber->onCodeToSessionRequest($event);
    }

    public function testOnCodeToSessionRequestWithValidParameters(): void
    {
        $event = $this->createMock(CodeToSessionResponseEvent::class);
        
        $launchOption = $this->createMock(LinkInterface::class);
        $launchOption->expects($this->once())
            ->method('getAttributes')
            ->willReturn([WechatMiniProgramShareBundle::PARAM_KEY => 'encoded_value']);

        $this->launchOptionHelper->expects($this->once())
            ->method('parseEvent')
            ->with($event)
            ->willReturn($launchOption);

        $this->hashids->expects($this->once())
            ->method('decode')
            ->with('encoded_value')
            ->willReturn(['user123', 1672531200]);

        $bizUser = $this->createMock(UserInterface::class);
        $this->userLoader->expects($this->once())
            ->method('loadUserByIdentifier')
            ->with('user123')
            ->willReturn($bizUser);

        $wechatUserEntity = $this->createMock(WechatUser::class);
        $wechatUserEntity->expects($this->once())
            ->method('getOpenId')
            ->willReturn('share_openid');

        $this->userRepository->expects($this->once())
            ->method('transformToWechatUser')
            ->with($bizUser)
            ->willReturn($wechatUserEntity);

        $visitWechatUser = $this->createMock(WechatUser::class);
        $visitWechatUser->expects($this->once())
            ->method('getOpenId')
            ->willReturn('visit_openid');

        $visitBizUser = $this->createMock(UserInterface::class);

        $event->expects($this->once())
            ->method('getWechatUser')
            ->willReturn($visitWechatUser);
        $event->expects($this->once())
            ->method('getBizUser')
            ->willReturn($visitBizUser);
        $event->expects($this->once())
            ->method('isNewUser')
            ->willReturn(true);
        $event->expects($this->any())
            ->method('getLaunchOptions')
            ->willReturn(['path' => '/pages/home']);
        $event->expects($this->any())
            ->method('getEnterOptions')
            ->willReturn(['path' => '/pages/home']);

        $this->doctrineService->expects($this->once())
            ->method('asyncInsert')
            ->with($this->isInstanceOf(InviteVisitLog::class));

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(InviteUserEvent::class));

        $this->logger->expects($this->once())
            ->method('info')
            ->with('获取邀请分享参数');

        $this->subscriber->onCodeToSessionRequest($event);
    }

    public function testOnCodeToSessionRequestWithSelfInvitation(): void
    {
        $event = $this->createMock(CodeToSessionResponseEvent::class);
        
        $launchOption = $this->createMock(LinkInterface::class);
        $launchOption->expects($this->once())
            ->method('getAttributes')
            ->willReturn([WechatMiniProgramShareBundle::PARAM_KEY => 'encoded_value']);

        $this->launchOptionHelper->expects($this->once())
            ->method('parseEvent')
            ->with($event)
            ->willReturn($launchOption);

        $this->hashids->expects($this->once())
            ->method('decode')
            ->with('encoded_value')
            ->willReturn(['user123', 1672531200]);

        $bizUser = $this->createMock(UserInterface::class);
        $this->userLoader->expects($this->once())
            ->method('loadUserByIdentifier')
            ->with('user123')
            ->willReturn($bizUser);

        $wechatUserEntity = $this->createMock(WechatUser::class);
        $wechatUserEntity->expects($this->exactly(2))
            ->method('getOpenId')
            ->willReturn('same_openid');

        $this->userRepository->expects($this->once())
            ->method('transformToWechatUser')
            ->with($bizUser)
            ->willReturn($wechatUserEntity);

        $event->expects($this->once())
            ->method('getWechatUser')
            ->willReturn($wechatUserEntity);

        $this->logger->expects($this->once())
            ->method('warning')
            ->with('分享人是不能邀请自己的');

        $this->subscriber->onCodeToSessionRequest($event);
    }
}