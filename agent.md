# Currencing agent instructions

Currencing is a separate Smart Responsor Symfony component.

## Canon

Use the default Symfony namespace:

```text
App\...
```

Do not migrate this component to plain `App\...`.

## Source-tree rules

Use Symfony-oriented type layers:

```text
src/Entity/Currency
src/Repository/Currency
src/Dto/Currency
src/ValueObject/Currency
src/Service/Currency
src/ServiceInterface/Currency
src/Controller/Currency
src/Form/Currency
src/Validator/Currency
```

Forbidden:

```text
src/Domain
Port/Adapter pattern
flat Currency/ legacy folder
FX provider logic inside Currencing
```

## Responsibility

Currencing owns currency metadata, ISO codes, minor units, formatting, normalization,
rounding policy selection, and conversion intent validation.

Currencing does not own exchange rates, historical FX, payment providers, tax calculation,
order totals, invoice totals, or discount rules.

## Doctrine

Entity-first catalog model:

```text
Entity: App\Entity\Currency\Currency
Table: currency_currency
Repository: App\Repository\Currency\CurrencyRepository
```

All Currencing-owned Doctrine tables must start with `currency_`.

## Neighbor components

Neighboring components should use DTOs and service interfaces. They must not consume
Currencing Doctrine entities directly.
