<?php

declare(strict_types=1);

namespace App\Currencing\Entity\Currency;

use App\Currencing\Repository\CurrencyRepository;
use App\Currencing\ValueObject\CurrencyCode;
use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectTitleEmbeddableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ORM\Table(name: 'currency_currency')]
#[ORM\UniqueConstraint(name: 'uniq_currency_currency_code', columns: ['code'])]
#[ORM\Index(name: 'idx_currency_currency_active_code', columns: ['active', 'code'])]
#[ORM\Index(name: 'currency_idx', columns: ['code'])]
class CurrencyEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectStateEmbeddableTrait;
    use ObjectTitleEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 3, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Currency]
    private string $code;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    #[Assert\Length(exactly: 3)]
    private ?string $numericCode = null;

    #[ORM\Column(type: 'smallint')]
    #[Assert\Range(min: 0, max: 8)]
    private int $minorUnit = 2;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    #[Assert\Length(max: 16)]
    private ?string $symbol = null;

    public function __construct(string|CurrencyCode $code = 'USD')
    {
        $this->initializeObjectIdentity();
        $this->initializeObjectAudit();
        $this->initializeObjectState(true, true, 'active');
        $this->initializeObjectTitle();
        $this->setCode($code);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCurrencyCode(): CurrencyCode
    {
        return new CurrencyCode($this->code);
    }

    public function setCode(string|CurrencyCode $code): self
    {
        $this->code = $code instanceof CurrencyCode ? $code->value() : CurrencyCode::fromString($code)->value();

        return $this;
    }

    public function getNumericCode(): ?string
    {
        return $this->numericCode;
    }

    public function setNumericCode(?string $numericCode): self
    {
        $normalized = $this->nullableTrim($numericCode);

        if (null !== $normalized && !preg_match('/^[0-9]{3}$/', $normalized)) {
            throw new \InvalidArgumentException('Currency numeric code must contain exactly three digits.');
        }

        $this->numericCode = $normalized;

        return $this;
    }

    public function getMinorUnit(): int
    {
        return $this->minorUnit;
    }

    public function setMinorUnit(int $minorUnit): self
    {
        if ($minorUnit < 0 || $minorUnit > 8) {
            throw new \InvalidArgumentException('Currency minor unit must be between 0 and 8.');
        }

        $this->minorUnit = $minorUnit;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $this->nullableTrim($symbol);

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->getFirstTitle();
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->setFirstTitle($this->nullableTrim($displayName));
        $this->touchModified();

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isObjectActive();
    }

    public function setActive(bool $active): self
    {
        $this->setObjectActive($active);
        $this->setObjectStatus($active ? 'active' : 'inactive');
        $this->touchModified();

        return $this;
    }

    public function activate(): self
    {
        return $this->setActive(true);
    }

    public function deactivate(): self
    {
        return $this->setActive(false);
    }

    private function nullableTrim(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $trimmed = trim($value);

        return '' === $trimmed ? null : $trimmed;
    }
}
