# Currencing M4: formatting and UI bridge readiness

Currencing now exposes UI-safe output contracts without leaking Doctrine entities to forms, API presenters, Interfacing, or templating layers.

## Added responsibility

Currencing owns reusable display preparation for currencies and normalized money values:

- currency selector view models;
- form choice labels;
- currency metadata views;
- formatted money display values;
- normalized decimal display strings from minor units.

## Boundary

This milestone still does **not** introduce FX conversion, exchange-rate providers, historical rates, provider sync, or conversion quotes. Those belong to a future Exchanging component.

## Primary contracts

- `CurrencyChoiceProviderInterface`
- `CurrencyMetadataViewProviderInterface`
- `CurrencySelectorViewProviderInterface`
- `MoneyDisplayFormatterInterface`

## UI-safe DTOs

- `CurrencyChoice`
- `CurrencyMetadataView`
- `CurrencySelectorView`
- `MoneyDisplay`

These DTOs are intentionally read-only and expose `toArray()` for template bridges and generic UI contracts.

## Consumer usage

Neighboring components should depend on the service interfaces, not on Doctrine entities:

- Ordering can render order-total currency selectors.
- Paying can render PSP amount displays.
- Taxating can render taxable/tax amounts.
- Interfacing can build reusable selector panels.
- Subscription can render recurring price labels.
