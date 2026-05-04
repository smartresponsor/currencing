# Currencing M9: Admin/demo surface

M9 adds a lightweight Symfony presentation surface for host-app verification.

## Intent

The goal is not to create a heavy back-office module. Currencing is a shared monetary
capability, so the UI surface should prove that the component is wired correctly:

- currency catalog metadata can be read;
- selector DTOs can be rendered;
- money normalization can be previewed;
- formatting output is visible;
- Doctrine entities are not leaked to Twig/API consumers.

## Routes

```text
GET /currencing/demo
GET /currencing/admin-preview/currencies
```

## Boundary

Currencing presentation uses service contracts and DTOs:

- `CurrencySelectorViewProviderInterface`
- `CurrencyMetadataViewProviderInterface`
- `MonetaryAmountInputResolverInterface`

The catalog remains Entity-first internally through Doctrine, but rendered output is
view/DTO-derived.

## Non-goals

M9 does not add:

- currency CRUD;
- FX/rate conversion;
- provider sync;
- historical exchange rates;
- payment behavior;
- order/invoice/tax logic.

CRUD can be added later if there is a concrete administrative requirement.
