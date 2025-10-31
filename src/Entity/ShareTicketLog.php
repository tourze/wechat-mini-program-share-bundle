<?php

namespace WechatMiniProgramShareBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use WechatMiniProgramShareBundle\Repository\ShareTicketLogRepository;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/share.html
 */
#[ORM\Table(name: 'ims_member_share_ticket_report', options: ['comment' => 'ShareTicket'])]
#[ORM\Entity(repositoryClass: ShareTicketLogRepository::class, readOnly: true)]
class ShareTicketLog implements \Stringable
{
    use SnowflakeKeyAware;

    #[IndexColumn]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeImmutable $createTime = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '用户ID'])]
    #[IndexColumn]
    #[Assert\Type(type: 'int')]
    #[Assert\PositiveOrZero]
    private int $memberId = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '分享者用户ID'])]
    #[IndexColumn]
    #[Assert\Type(type: 'int')]
    #[Assert\PositiveOrZero]
    private int $shareMemberId = 0;

    #[ORM\Column(type: Types::STRING, length: 200, nullable: false, options: ['default' => '', 'comment' => '群标识'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 200)]
    private string $openGid = '';

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '分享时间'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $shareTime = null;

    public function setCreateTime(?\DateTimeImmutable $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getCreateTime(): ?\DateTimeImmutable
    {
        return $this->createTime;
    }

    public function getMemberId(): int
    {
        return $this->memberId;
    }

    public function setMemberId(int $memberId): void
    {
        $this->memberId = $memberId;
    }

    public function getShareMemberId(): int
    {
        return $this->shareMemberId;
    }

    public function setShareMemberId(int $shareMemberId): void
    {
        $this->shareMemberId = $shareMemberId;
    }

    public function getOpenGid(): string
    {
        return $this->openGid;
    }

    public function setOpenGid(string $openGid): void
    {
        $this->openGid = $openGid;
    }

    public function getShareTime(): ?\DateTimeImmutable
    {
        return $this->shareTime;
    }

    public function setShareTime(?\DateTimeImmutable $shareTime): void
    {
        $this->shareTime = $shareTime;
    }

    public function __toString(): string
    {
        return sprintf('ShareTicketLog[%s]', $this->id ?? 'new');
    }
}
