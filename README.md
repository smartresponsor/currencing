# Currencing

Currencing is a standalone Symfony-oriented shared business capability for currency and money metadata.

It owns:

- ISO 4217 currency codes
- supported currency validation
- minor units / precision
- display metadata
- formatting
- decimal-to-minor-unit normalization
- normalized money amount DTOs
- UI-safe selector/metadata view DTOs
- template-safe formatted money displays
- Doctrine-backed currency catalog
- neighbor-component monetary input resolution

It intentionally does not own FX conversion. Exchange rates and historical conversion belong to a separate Exchanging component that can depend on Currencing.

## Symfony-oriented naming convention

- Entity: `src/Entity/Currency/*`
- Repository: `src/Repository/Currency/*`
- DTO: `src/Dto/Currency/*`
- Value Object: `src/ValueObject/Currency/*`
- Service: `src/Service/Currency/*`
- ServiceInterface: `src/ServiceInterface/Currency/*`
- Doctrine table prefix: `currency_*`
- Canonical table: `currency_currency`

## Main contracts

- `App\ServiceInterface\Currency\CurrencyMetadataProviderInterface`
- `App\ServiceInterface\Currency\CurrencyCodeValidatorInterface`
- `App\ServiceInterface\Currency\CurrencyPrecisionResolverInterface`
- `App\ServiceInterface\Currency\MoneyAmountNormalizerInterface`
- `App\ServiceInterface\Currency\MoneyNormalizerInterface`
- `App\ServiceInterface\Currency\CurrencyFormatterInterface`
- `App\ServiceInterface\Currency\MoneyDisplayFormatterInterface`
- `App\ServiceInterface\Currency\CurrencyChoiceProviderInterface`
- `App\ServiceInterface\Currency\CurrencyMetadataViewProviderInterface`
- `App\ServiceInterface\Currency\CurrencySelectorViewProviderInterface`
- `App\ServiceInterface\Currency\MonetaryAmountInputResolverInterface`

## Main model

- `App\Entity\Currency\Currency`
- `App\ValueObject\Currency\CurrencyCode`
- `App\Dto\Currency\MoneyAmount`
- `App\Dto\Currency\MoneyDisplay`
- `App\Dto\Currency\CurrencyChoice`
- `App\Dto\Currency\CurrencyMetadataView`
- `App\Dto\Currency\CurrencySelectorView`
- `App\Dto\Currency\MonetaryAmountInput`
- `App\Dto\Currency\MonetaryAmountResolution`

## Boundary

Currencing validates and normalizes money values. Exchanging should own rates, live conversion, historical quotes, and provider synchronization.


## Neighbor integration flow

Use `MonetaryAmountInputResolverInterface` when Ordering, Paying, Taxating,
Shipping, Subscription, Discounting, Coupon, or Promotion needs to hand raw
monetary input to Currencing and receive a canonical minor-unit amount plus a
UI-safe display DTO.


## M7 Monetary policy layer

Currencing now includes a Symfony-first monetary policy layer for selecting rounding behavior without moving FX/rate conversion into this component.

Canonical boundaries:

- `MoneyRoundingPolicyResolverInterface` resolves named/context policies.
- `MonetaryAmountInput` may specify a policy name or a business context.
- `MonetaryAmountInputResolverInterface` returns `MonetaryAmountResolution` with the selected policy.
- Default canonical behavior remains strict/reject.

Named policy examples:

- `canonical.reject`
- `ordering.reject`
- `paying.reject`
- `taxating.reject`
- `formatting.half_up`
- `discounting.half_up`

FX rates and currency conversion remain outside Currencing and belong to Exchanging.


## M8 API/read endpoints

Currencing now exposes small Symfony read/normalization endpoints:

```text
GET  /currencing/currencies
GET  /currencing/currencies/{code}
GET  /currencing/currency-selector
POST /currencing/money/normalize
```

These endpoints are DTO/VO-backed and do not leak Doctrine entities to UI/API consumers. FX/rate conversion remains outside Currencing.
## M9 admin/demo surface

Currencing includes a lightweight verification surface:

```text
GET /currencing/demo
GET /currencing/admin-preview/currencies
```

These routes are intentionally read/demo oriented. They prove currency selector output,
currency metadata views, money normalization, and display formatting without creating a
heavy admin module or leaking Doctrine entities into presentation code.
## M10 Exchanging boundary handshake

Currencing now exposes an explicit conversion boundary:

```text
GET /currencing/conversion-boundary
```

Currencing may validate conversion intent shape, but it does not fetch rates or calculate
FX quotes. Exchanging owns rate sourcing, historical rates, conversion quotes, and converted
amount calculation. Dependency direction is fixed: Exchanging may depend on Currencing;
Currencing must not depend on Exchanging.
## M11 release/readiness manifests

Currencing now includes machine-readable and human-readable delivery metadata:

```text
manifest.yaml
agent.md
docs/currencing/install.md
docs/currencing/inventory.md
docs/currencing/readiness.md
delivery/release/README.md
```

These files make the component easier to inspect, hand off, import, and validate without
depending on chat history.
## M12 structural gates

Currencing now includes a framework-free structural gate:

```bash
php tools/currencing-structure-check.php
```

The gate checks namespace canon, required type layers, forbidden legacy directories,
canonical Doctrine table naming, migration table prefixing, and absence of FX provider
logic inside Currencing.
## M13 runtime/container hardening

Currencing now includes a framework-free runtime smoke gate:

```bash
php tools/currencing-runtime-smoke-check.php
```

Use it together with the structural gate before Symfony container proof:

```bash
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
composer dump-autoload
php bin/console cache:clear
```


## M14 service/autowiring and Doctrine readiness

Currencing now includes explicit Symfony config:

```text
config/services/currencing.yaml
config/packages/doctrine_currencing.yaml
```

And a new framework-free smoke gate:

```bash
php tools/currencing-autoload-smoke-check.php
```


## M15 API contract readiness

Currencing now includes API contract artifacts:

```text
docs/api/currencing.openapi.yaml
docs/api/currencing.http
docs/api/currencing-endpoints.md
delivery/release/currencing-endpoints.json
```

Run:

```bash
php tools/currencing-api-contract-check.php
```


## M16 release-candidate closure

Currencing now includes RC closure metadata:

```text
delivery/release/currencing-rc-readiness.json
docs/currencing/release-candidate-checklist.md
docs/currencing/command-matrix.md
```

Run:

```bash
php tools/currencing-release-candidate-check.php
```

## M17 Runtime RC Proof Foundation

Currencing now includes a minimal default-Symfony runtime foundation for local RC proof: `composer.json`, `src/Kernel.php`, `bin/console`, explicit route/service imports, Doctrine configuration, and PostgreSQL-first migrations. Run `php tools/currencing-standalone-runtime-foundation-check.php` before the Symfony container proof commands.


## M18 Service alias closure gate

Currencing now includes a framework-free service alias closure gate:

```bash
php tools/currencing-service-alias-closure-check.php
```

It scans constructor-injected `App\ServiceInterface\Currency\*Interface` contracts and verifies explicit aliases in `config/services/currencing.yaml` before local Symfony container proof.

## M19 Console Runtime Proof Gate

Currencing now includes a static console-runtime proof gate:

```bash
php tools/currencing-console-runtime-proof-check.php
```

It verifies Composer runtime requirements, `bin/console`, `public/index.php`, Kernel, route imports, service imports, Doctrine mapping, Twig namespace, templates, and `.env` markers before vendor-backed Symfony boot.

## M20 LazyGhost VarExporter runtime fix

M20 closes the first real local runtime blocker found during `cache:clear`:

```text
Symfony LazyGhost is not available.
```

`composer.json` now requires `symfony/var-exporter` and the console/runtime gates verify the dependency before the Symfony container boot proof.



## M21 database runtime proof

After M20, the Symfony runtime proof reaches Doctrine database validation. If PostgreSQL rejects the placeholder `app/app` credentials, copy `.env.local.example` to `.env.local` and configure a valid local PostgreSQL DSN. See `docs/currencing/local-postgresql-proof.md`.

```powershell
php tools/currencing-database-runtime-proof-check.php
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:schema:validate
```
