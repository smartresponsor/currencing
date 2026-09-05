<?php

declare(strict_types=1);

namespace App\Entity\Currency;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLocaleEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use App\Repository\Currency\CurrencyTranslationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyTranslationRepository::class)]
#[ORM\Table(name: 'currency_translation')]
#[ORM\UniqueConstraint(name: 'uniq_currency_translation_currency_locale', columns: ['currency_id', 'object_locale'])]
#[ORM\Index(name: 'idx_currency_translation_locale', columns: ['object_locale'])]
final class CurrencyTranslationEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectLocaleEmbeddableTrait;
    use ObjectStateEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CurrencyEntity::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private CurrencyEntity $currency;

    #[ORM\Column(type: 'string', length: 128)]
    private string $displayName;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private ?string $symbol = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    public function __construct(CurrencyEntity $currency, string $locale, string $displayName, ?string $symbol = null)
    {
        $this->initializeObjectIdentity();
        $this->initializeObjectAudit();
        $this->initializeObjectLocale($locale);
        $this->initializeObjectState(true, true, 'active');
        $this->currency = $currency;
        $this->displayName = trim($displayName);
        $this->symbol = $this->nullableTrim($symbol);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): CurrencyEntity
    {
        return $this->currency;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = trim($displayName);
        $this->touchModified();

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $this->nullableTrim($symbol);
        $this->touchModified();

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $this->nullableTrim($description);
        $this->touchModified();

        return $this;
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
