# Currencing M8 — API/read endpoints

M8 adds small Symfony read/normalization controllers over the existing DTO/service contracts.

## Endpoints

```text
GET  /currencing/currencies
GET  /currencing/currencies/{code}
GET  /currencing/currency-selector
POST /currencing/money/normalize
```

## Boundary

The controllers return DTO-derived arrays. They do not expose Doctrine entities and do not introduce FX/rate conversion.

Currencing still owns:

```text
currency metadata
ISO code validation
minor-unit precision
money normalization
formatting output
rounding policy selection
```

Exchanging still owns:

```text
exchange rates
conversion quotes
rate providers
historical FX
```

## Money normalization payload

```json
{
  "amount": "12.34",
  "currencyCode": "USD",
  "roundingMode": "reject",
  "roundingContext": "ordering",
  "locale": "en_US",
  "sourceComponent": "Ordering",
  "sourceReference": "order-line"
}
```

The response includes canonical minor units, decimal display amount, formatted amount, and selected rounding policy metadata.
