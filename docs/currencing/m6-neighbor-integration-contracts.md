# Currencing M6: Neighbor integration contracts

M6 adds a narrow integration surface for components that need money validation,
normalization, precision, and display metadata without depending on Currencing
Doctrine entities.

## Canonical consumer flow

Neighboring components should pass monetary input through:

- `App\Currencing\DTO\CurrencyAmountInputDTO`
- `App\ServiceInterface\Currency\CurrencyAmountInputResolverInterface`

The resolver returns:

- `App\Currencing\DTO\CurrencyAmountResolutionDTO`
- canonical `CurrencyAmountDTO` in minor units
- template-safe `CurrencyDisplayDTO`
- resolved currency minor unit

## Intended consumers

- Ordering: order totals, line totals, adjustments
- Paying: payment amount payload preparation
- Taxating: taxable amounts and calculated tax amounts
- Shipping: shipping rates and surcharges
- Subscription: recurring price amounts
- Discounting / Coupon / Promotion: discount values

## Boundary rule

Consumers should not read or persist `App\Entity\Currency\CurrencyEntity` directly for
money calculations. Doctrine entity access belongs inside Currencing persistence
and catalog services.

Consumers should depend on:

- `CurrencyCodeValidatorInterface`
- `CurrencyPrecisionResolverInterface`
- `CurrencyAmountNormalizerInterface`
- `CurrencyDisplayFormatterInterface`
- `CurrencyAmountInputResolverInterface`

## No FX responsibility

M6 still does not add exchange rates, conversion quotes, provider sync, or
historical rates. Those belong to Exchanging.
