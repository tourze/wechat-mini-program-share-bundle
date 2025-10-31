<?php

namespace WechatMiniProgramShareBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Enum\EnvVersion;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

/**
 * 分享码
 */
#[ORM\Entity(repositoryClass: ShareCodeRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_share_code', options: ['comment' => '分享码'])]
class ShareCode implements \Stringable
{
    use IpTraceableAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 2000, nullable: true, options: ['comment' => '链接地址'])]
    #[Assert\Url]
    #[Assert\Length(max: 2000)]
    private ?string $linkUrl = null;

    #[ORM\Column(type: Types::STRING, length: 2000, nullable: true, options: ['comment' => '图片地址'])]
    #[Groups(groups: ['restful_read'])]
    #[Assert\Url]
    #[Assert\Length(max: 2000)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true, enumType: EnvVersion::class, options: ['comment' => '环境版本'])]
    #[Assert\Type(type: EnvVersion::class)]
    #[Assert\Choice(callback: [EnvVersion::class, 'cases'])]
    private ?EnvVersion $envVersion = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否有效', 'default' => 1])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?UserInterface $user = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '大小'])]
    #[Assert\Type(type: 'int')]
    #[Assert\PositiveOrZero]
    private ?int $size = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getLinkUrl(): ?string
    {
        return $this->linkUrl;
    }

    public function setLinkUrl(string $linkUrl): void
    {
        $this->linkUrl = $linkUrl;
    }

    public function getEnvVersion(): ?EnvVersion
    {
        return $this->envVersion;
    }

    public function setEnvVersion(?EnvVersion $envVersion): void
    {
        $this->envVersion = $envVersion;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function __toString(): string
    {
        return sprintf('ShareCode[%s]', 0 === $this->id ? 'new' : $this->id);
    }
}
