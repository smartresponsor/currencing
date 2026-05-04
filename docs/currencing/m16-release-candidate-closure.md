# Currencing M16: Release candidate closure

M16 closes the architecture/business development sequence for Currencing and packages
the component as a release-candidate candidate for local runtime proof.

## Status

```text
release-candidate-architecture-business-complete
```

## Current readiness

Architecture/business completeness: **92%**

This percentage intentionally excludes:

- local runtime proof;
- host-app cache clear proof;
- real database migration execution;
- full PHPUnit execution;
- PHPStan/Psalm proof;
- vendor-backed CI;
- browser/UI proof.

## Why not 100%

Remaining work is proof/hardening in the target environment:

```text
composer dump-autoload
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
php tools/currencing-api-contract-check.php
php bin/console cache:clear
php bin/console debug:router | findstr currencing
php bin/console debug:container App\ServiceInterface\Currency
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

## Architecture/business scope completed

Currencing now owns:

- ISO currency code validation;
- currency metadata;
- minor-unit precision;
- money normalization;
- money display formatting;
- selector/read output;
- monetary rounding policy;
- neighbor integration contracts;
- Currencing/Exchanging boundary metadata;
- API contract artifacts;
- structural/runtime smoke gates.

Currencing explicitly does not own:

- exchange-rate sourcing;
- historical FX;
- conversion quote calculation;
- payment provider behavior;
- order/tax/discount business rules;
- heavy admin CRUD.

## RC artifacts

```text
delivery/release/currencing-rc-readiness.json
docs/currencing/release-candidate-checklist.md
docs/currencing/command-matrix.md
```
