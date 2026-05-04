# Currencing API endpoints

## Read endpoints

```text
GET /currencing/currencies
GET /currencing/currencies/{code}
GET /currencing/currency-selector
GET /currencing/conversion-boundary
```

## Command-like read/normalization endpoint

```text
POST /currencing/money/normalize
```

This endpoint normalizes a supplied decimal string and ISO currency code into canonical
minor units. It does not create orders, payments, taxes, invoices, exchange quotes, or
database rows.

## Route names

```text
currencing_currency_catalog
currencing_currency_metadata
currencing_currency_selector
currencing_money_normalize
currencing_conversion_boundary
```

Demo/presentation routes:

```text
currencing_demo_index
currencing_admin_preview_currencies
```

## Contract files

```text
docs/api/currencing.openapi.yaml
docs/api/currencing.http
```

## Boundary

The API exposes DTO/view-derived data. It does not expose Doctrine entities as API
contracts.
