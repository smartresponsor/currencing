# Currencing M2/M3: metadata contracts and money normalization

## Purpose

This wave turns Currencing from a simple currency metadata holder into a reusable shared business capability for neighboring Symfony components.

## Currencing owns

- ISO 4217 currency identity.
- Supported currency validation.
- Currency metadata lookup.
- Minor-unit precision resolution.
- Decimal money parsing into minor units.
- Minor-unit formatting back to decimal strings.
- Normalized immutable money amount DTOs.

## Currencing does not own

- Exchange rates.
- Historical FX conversion.
- Payment authorization.
- Order totals.
- Tax calculation.
- Discount rules.

Those responsibilities should consume Currencing contracts instead of duplicating currency logic.

## Primary consumer-facing contracts

- `CurrencyMetadataProviderInterface`
- `CurrencyCodeValidatorInterface`
- `CurrencyPrecisionResolverInterface`
- `MoneyAmountNormalizerInterface`
- `MoneyNormalizerInterface`
- `CurrencyFormatterInterface`

## Rounding boundary

`MoneyAmountNormalizerInterface` defaults to reject over-precise decimal input. This is safer for business workflows. The legacy `MoneyNormalizerInterface` keeps half-up behavior for compatibility with the first foundation wave.
