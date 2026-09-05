<?php

declare(strict_types=1);

namespace App\Entity\Currency;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSourceEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use App\Repository\Currency\CurrencyExchangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyExchangeRepository::class)]
#[ORM\Table(name: 'currency_exchange')]
#[ORM\Index(name: 'idx_currency_exchange_pair_effective', columns: ['base_currency_code', 'target_currency_code', 'effective_at'])]
#[ORM\Index(name: 'idx_currency_exchange_quote', columns: ['quote_currency_code'])]
final class CurrencyExchangeEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSourceEmbeddableTrait;
    use ObjectStateEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'base_currency_code', type: 'string', length: 3)]
    private string $baseCurrencyCode;

    #[ORM\Column(name: 'target_currency_code', type: 'string', length: 3)]
    private string $targetCurrencyCode;

    #[ORM\Column(name: 'quote_currency_code', type: 'string', length: 3)]
    private string $quoteCurrencyCode;

    #[ORM\Column(name: 'exchange_rate', type: 'decimal', precision: 18, scale: 8)]
    private string $exchangeRate;

    #[ORM\Column(name: 'effective_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $effectiveAt;

    public function __construct(
        string $baseCurrencyCode,
        string $targetCurrencyCode,
        string $quoteCurrencyCode,
        string $exchangeRate,
        ?\DateTimeImmutable $effectiveAt = null,
    ) {
        $this->initializeObjectIdentity();
        $this->initializeObjectAudit();
        $this->initializeObjectSource();
        $this->initializeObjectState(true, true, 'active');
        $this->baseCurrencyCode = $this->normalizeCurrencyCode($baseCurrencyCode);
        $this->targetCurrencyCode = $this->normalizeCurrencyCode($targetCurrencyCode);
        $this->quoteCurrencyCode = $this->normalizeCurrencyCode($quoteCurrencyCode);
        $this->exchangeRate = $exchangeRate;
        $this->effectiveAt = $effectiveAt ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseCurrencyCode(): string
    {
        return $this->baseCurrencyCode;
    }

    public function getTargetCurrencyCode(): string
    {
        return $this->targetCurrencyCode;
    }

    public function getQuoteCurrencyCode(): string
    {
        return $this->quoteCurrencyCode;
    }

    public function getExchangeRate(): string
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(string $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;
        $this->touchModified();

        return $this;
    }

    public function getEffectiveAt(): \DateTimeImmutable
    {
        return $this->effectiveAt;
    }

    private function normalizeCurrencyCode(string $code): string
    {
        $code = strtoupper(trim($code));
        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new \InvalidArgumentException('Currency code must be an ISO 4217 alpha-3 code.');
        }

        return $code;
    }
}
