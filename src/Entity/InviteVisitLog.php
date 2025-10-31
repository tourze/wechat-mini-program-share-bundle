<?php

namespace WechatMiniProgramShareBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use WechatMiniProgramBundle\Entity\LaunchOptionsAware;
use WechatMiniProgramShareBundle\Repository\InviteVisitLogRepository;

#[ORM\Entity(repositoryClass: InviteVisitLogRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_invite_visit_log', options: ['comment' => '邀请访问记录'])]
class InviteVisitLog implements \Stringable
{
    use IpTraceableAware;
    use LaunchOptionsAware;
    use SnowflakeKeyAware;

    /**
     * @var array<string, mixed>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '上下文'])]
    #[Assert\Type(type: 'array')]
    private ?array $context = [];

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '分享者openId'])]
    #[IndexColumn]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $shareOpenId = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?UserInterface $shareUser = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '分享时间'])]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $shareTime = null;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '访问者openId'])]
    #[IndexColumn]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $visitOpenId = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['comment' => '受邀时间'])]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $visitTime = null;

    #[ORM\Column(type: Types::STRING, length: 2000, options: ['comment' => '访问地址'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 2000)]
    private ?string $visitPath = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?UserInterface $visitUser = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否为新用户', 'default' => false])]
    #[IndexColumn]
    #[Assert\Type(type: 'bool')]
    private bool $newUser = false;

    #[ORM\Column(nullable: true, options: ['comment' => '是否已注册', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $registered = null;

    /**
     * @return array<string, mixed>|null
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @param array<string, mixed>|null $context
     */
    public function setContext(?array $context): void
    {
        $this->context = $context;
    }

    public function getShareOpenId(): ?string
    {
        return $this->shareOpenId;
    }

    public function setShareOpenId(string $shareOpenId): void
    {
        $this->shareOpenId = $shareOpenId;
    }

    public function getShareTime(): ?\DateTimeImmutable
    {
        return $this->shareTime;
    }

    public function setShareTime(\DateTimeImmutable $shareTime): void
    {
        $this->shareTime = $shareTime;
    }

    public function getVisitOpenId(): ?string
    {
        return $this->visitOpenId;
    }

    public function setVisitOpenId(string $visitOpenId): void
    {
        $this->visitOpenId = $visitOpenId;
    }

    public function getVisitTime(): ?\DateTimeImmutable
    {
        return $this->visitTime;
    }

    public function setVisitTime(\DateTimeImmutable $visitTime): void
    {
        $this->visitTime = $visitTime;
    }

    public function getVisitPath(): ?string
    {
        return $this->visitPath;
    }

    public function setVisitPath(string $visitPath): void
    {
        $this->visitPath = $visitPath;
    }

    public function isNewUser(): bool
    {
        return $this->newUser;
    }

    public function setNewUser(bool $newUser): void
    {
        $this->newUser = $newUser;
    }

    public function getVisitUser(): ?UserInterface
    {
        return $this->visitUser;
    }

    public function setVisitUser(?UserInterface $visitUser): void
    {
        $this->visitUser = $visitUser;
    }

    public function getShareUser(): ?UserInterface
    {
        return $this->shareUser;
    }

    public function setShareUser(?UserInterface $shareUser): void
    {
        $this->shareUser = $shareUser;
    }

    public function isRegistered(): ?bool
    {
        return $this->registered;
    }

    public function setRegistered(?bool $registered): void
    {
        $this->registered = $registered;
    }

    public function __toString(): string
    {
        return sprintf('InviteVisitLog[%s]', $this->id ?? 'new');
    }
}
