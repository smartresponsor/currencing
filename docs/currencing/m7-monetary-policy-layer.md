# Currencing M7: Monetary Policy Layer

M7 adds a Symfony-first monetary policy layer.

## Purpose

Currencing must normalize money consistently, but different consumers may need different business policies:

- canonical storage should usually reject over-precise amounts;
- payments should match provider minor-unit precision exactly;
- tax inputs should stay explicit and auditable;
- display-only projections may opt into rounding;
- discount calculations may choose a policy deliberately.

## Boundary

Currencing owns:

- policy names;
- policy context selection;
- rounding mode selection;
- normalization policy resolution.

Currencing does not own:

- exchange rates;
- historical conversion;
- provider rate synchronization;
- FX quote lifecycle.

Those belong to Exchanging.

## Naming convention

- DTO: `src/Dto/Currency/MoneyRoundingPolicy.php`
- Enum: `src/Enum/Currency/MoneyRoundingContext.php`
- VO: `src/ValueObject/Currency/MoneyRoundingPolicyName.php`
- Service: `src/Service/Currency/MoneyRoundingPolicyResolver.php`
- Interface: `src/ServiceInterface/Currency/MoneyRoundingPolicyResolverInterface.php`

## Default behavior

The default resolver behavior is conservative:

- no policy/context on input: use the input rounding mode;
- named policy: use exact named policy;
- context: resolve context to the canonical component policy;
- canonical/ordering/paying/taxating default to `reject`.

This avoids silent rounding unless a consumer opts in explicitly.
