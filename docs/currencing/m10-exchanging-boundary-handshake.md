# Currencing M10: Exchanging boundary handshake

M10 defines the stable boundary between Currencing and Exchanging.

## Rule

```text
Exchanging may depend on Currencing.
Currencing must not depend on Exchanging.
```

## Currencing owns

- ISO currency code validation;
- currency metadata;
- minor-unit precision;
- money amount normalization;
- money display formatting;
- rounding policy selection;
- conversion intent validation.

## Exchanging owns

- exchange-rate sourcing;
- provider synchronization;
- currency conversion quotes;
- historical exchange rates;
- FX spread/markup policy;
- converted amount calculation.

## New DTOs/contracts

```text
CurrencyConversionIntent
CurrencyConversionBoundary
CurrencyConversionBoundaryProviderInterface
```

`CurrencyConversionIntent` lets callers describe that a normalized amount should later be
converted by Exchanging. It validates source/target currency shape and prevents same-currency
conversion intents, but it does not fetch rates or calculate converted amounts.

## Endpoint

```text
GET /currencing/conversion-boundary
```

This endpoint exposes the boundary as JSON for host applications, documentation automation,
and future Smart Responsor import/review tooling.

## Non-goals

M10 does not add:

- `CurrencyConverter`;
- `ExchangeRateProvider`;
- provider adapters;
- historical FX storage;
- conversion quote calculation;
- payment provider conversion behavior.
