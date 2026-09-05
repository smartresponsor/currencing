# Currencing agent instructions

Currencing is a separate Smart Responsor Symfony-oriented component.

## Canon

- Use the default `App\...` namespace only. Do not introduce `App\Currencing\...`.
- Keep technical roles at the Symfony-oriented top level; do not introduce `src/Domain` or Ports/Adapters.
- `src/Service/Http/Currency` is canonical: the technical `Http` classification precedes the semantic `Currency` token.
- Inside a `/Currency/` semantic subtree, PHP type/file names start with `Currency`.
- Use `DTO`, `Interface`, `Entity`, `Controller`, `Exception` and other technical-role suffixes consistently.
- Keep `Service` ↔ `ServiceInterface` and `Repository` ↔ `RepositoryInterface` trees mirrored.

## Currency naming

Use `Currency` as the single ownership prefix. Do not duplicate it with `Money` or `Monetary` in PHP type names.

Canonical shape:

```text
Currency + [qualifier] + concept + role
```

Examples:

```text
CurrencyAmountDTO
CurrencyDisplayFormatter
CurrencyCanonicalAmountNormalizer
CurrencyIntlMetadataProvider
CurrencyRoundingPolicyResolverInterface
```

Public API vocabulary such as `/currencing/money/normalize` is a separate compatibility surface and is not renamed merely to satisfy PHP class naming.

## Responsibility

Currencing owns currency identity and metadata, ISO codes, minor-unit precision, amount normalization, display formatting, rounding policy selection, selector/read output, template bridge output, and conversion-boundary validation.

Currencing does not own exchange-rate sourcing, historical FX, conversion quote pricing, payment-provider behavior, order/tax/discount totals, or neighboring components' business rules.

## Doctrine

Currencing is entity-first. `App\Entity\Currency\CurrencyEntity` maps the canonical `currency_currency` table. Neighboring components consume DTOs and service interfaces, not Currencing Doctrine entities.

## Validation

Before release, run the repository-declared `composer currencing:gates` chain and PHPUnit. Do not invent alternate commands when repository scripts already define the proof path.
