# Currencing legacy map

## Keep as useful ideas

- ISO currency code choice for forms.
- Currency metadata entity with code/symbol/minor-unit fields.
- Fixtures for core currencies.
- Validators for currency pair constraints, but only if FX/exchange remains in a separate Exchanging component.
- Minor-unit conversion as a service-level responsibility.

## Rewrite / retire as legacy

- Flat `Currency/` folder with mixed Entity/Form/Service/Validator classes.
- `CurrencyEnUs` as a separate entity. Locale-specific names should be metadata/translation/read model, not another currency entity.
- `CurrencyExchange` inside Currencing. Exchange rates belong to Exchanging, not the base currency component.
- `CurrencyConverter` duplicates. One uses repository abstraction, another uses EntityManager directly. Neither should live in the base currency component if it performs FX conversion.
- Empty `CurrencyService`.
- Old generic traits/controllers/filters embedded into the entity before the core data model is stable.

## Symfony-oriented target

Currencing owns currency identity, metadata, formatting, and money normalization. Consumers such as Ordering, Paying, Taxating, Discounting, and Shipping should depend on service interfaces from this component instead of storing ad-hoc currency logic.
