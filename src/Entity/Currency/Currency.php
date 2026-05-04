<?php

declare(strict_types=1);

namespace App\Entity\Currency;

use App\Repository\Currency\CurrencyRepository;
use App\ValueObject\Currency\CurrencyCode;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ORM\Table(name: 'currency_currency')]
#[ORM\UniqueConstraint(name: 'uniq_currency_currency_code', columns: ['code'])]
#[ORM\Index(name: 'idx_currency_currency_active_code', columns: ['active', 'code'])]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
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

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    #[Assert\Length(max: 128)]
    private ?string $displayName = null;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    public function __construct(string|CurrencyCode $code = 'USD')
    {
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

        if ($normalized !== null && !preg_match('/^[0-9]{3}$/', $normalized)) {
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
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $this->nullableTrim($displayName);

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function activate(): self
    {
        $this->active = true;

        return $this;
    }

    public function deactivate(): self
    {
        $this->active = false;

        return $this;
    }

    private function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
