# Currencing inventory

## Main entity

```text
App\Currencing\Entity\Currency\CurrencyEntity
```

Canonical table:

```text
currency_currency
```

## Key DTOs

```text
CurrencyChoiceDTO
CurrencyMetadataViewDTO
CurrencySelectorViewDTO
CurrencyAmountDTO
CurrencyDisplayDTO
CurrencyAmountInputDTO
CurrencyAmountResolutionDTO
CurrencyRoundingPolicyDTO
CurrencyConversionIntentDTO
CurrencyConversionBoundaryDTO
CurrencyTemplateContextDTO
```

## Key value objects

```text
CurrencyCode
CurrencyConsumerName
CurrencyRoundingPolicyName
```

## Key services

```text
CurrencyMetadataProvider
CurrencyPrecisionResolver
CurrencyChoiceProvider
CurrencyMetadataViewProvider
CurrencySelectorViewProvider
CurrencyDecimalParser
MoneyAmountNormalizer
CurrencyAmountInputResolver
CurrencyDisplayFormatter
CurrencyRoundingPolicyResolver
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
CurrencyDecimalParserInterface
CurrencyAmountNormalizerInterface
CurrencyAmountInputResolverInterface
CurrencyDisplayFormatterInterface
CurrencyRoundingPolicyResolverInterface
CurrencyConversionBoundaryProviderInterface
CurrencyTemplateContextProviderInterface
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
GET  /currencing/template-context
```

## Boundary

Currencing exposes validated, normalized monetary data to neighboring components.
It does not expose Doctrine entities as integration contracts.
