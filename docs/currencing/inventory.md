# Currencing inventory

## Main entity

```text
App\Entity\Currency\Currency
```

Canonical table:

```text
currency_currency
```

## Key DTOs

```text
CurrencyChoice
CurrencyMetadataView
CurrencySelectorView
MoneyAmount
MoneyDisplay
MonetaryAmountInput
MonetaryAmountResolution
MoneyRoundingPolicy
CurrencyConversionIntent
CurrencyConversionBoundary
```

## Key value objects

```text
CurrencyCode
CurrencyConsumerName
MoneyRoundingPolicyName
```

## Key services

```text
CurrencyMetadataProvider
CurrencyPrecisionResolver
CurrencyChoiceProvider
CurrencyMetadataViewProvider
CurrencySelectorViewProvider
DecimalMoneyParser
MoneyAmountNormalizer
MonetaryAmountInputResolver
MoneyDisplayFormatter
MoneyRoundingPolicyResolver
CurrencyConversionBoundaryProvider
```

## Public-ish service interfaces

```text
CurrencyMetadataProviderInterface
CurrencyPrecisionResolverInterface
CurrencyCodeValidatorInterface
CurrencyChoiceProviderInterface
CurrencyMetadataViewProviderInterface
CurrencySelectorViewProviderInterface
DecimalMoneyParserInterface
MoneyAmountNormalizerInterface
MonetaryAmountInputResolverInterface
MoneyDisplayFormatterInterface
MoneyRoundingPolicyResolverInterface
CurrencyConversionBoundaryProviderInterface
```

## Controllers/routes

```text
GET  /currencing/currencies
GET  /currencing/currencies/{code}
GET  /currencing/currency-selector
POST /currencing/money/normalize
GET  /currencing/demo
GET  /currencing/admin-preview/currencies
GET  /currencing/conversion-boundary
```

## Boundary

Currencing exposes validated, normalized monetary data to neighboring components.
It does not expose Doctrine entities as integration contracts.
