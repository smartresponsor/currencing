# Currencing readiness

## Architecture/business readiness

Current status: strong architectural foundation.

Implemented:

- separate component responsibility;
- default Symfony `App\...` namespace;
- Symfony-oriented type-layer source tree;
- Entity-first currency catalog;
- Doctrine table prefix canon;
- DTO/view output for consumers;
- value objects for codes/policy names/consumer names;
- money normalization;
- display formatting;
- selector/read API;
- monetary policy layer;
- neighbor integration contract;
- Exchanging boundary handshake;
- lightweight demo/read surface.

## Current verification posture

Local repository proof is green for the component-owned RC gates, PHPUnit, PHPStan, coding-style checks, strict Composer validation, PHPUnit/Xdebug coverage production, and a repository-local Playwright real-browser test of the standalone conversion-boundary route.

The current coverage evidence is factual rather than inferred: lines 45.55%, methods 30.54%, branches 71.12%. Canon040 therefore classifies line/method coverage as high test debt while branch coverage clears the 70% canonical threshold. This is remediation debt, not a hard executable-tooling failure.

The remaining environment/integration proofs are:

- host-application integration proof;
- the database-backed `/currencing/demo` browser path against a schema aligned with the current Entity model;
- production database migration execution;
- vendor-backed remote CI/deployment proof.

The current local PostgreSQL database is an unmanaged legacy development baseline: `currency_currency` exists with the older seven-column shape, contains zero rows, `currency_translation` is absent, and `doctrine_migration_versions` is empty. The only pending migration is the initial `CREATE TABLE` migration, so it must not be applied blindly over the existing table. The repository leaves that local database untouched until its baseline/ledger is reconciled safely.

## Risks to check during runtime hardening

- route import in host app;
- Twig namespace registration;
- Doctrine mapping path registration when installed as a component;
- service alias conflicts if host app overrides Currencing services;
- migration namespace/path registration;
- fixture ordering if host app has its own fixture loader.
