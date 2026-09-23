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

The current coverage evidence is factual rather than inferred: lines 53.75% (380/707), methods 36.23% (75/207), branches 86.63% (324/374). Canon040 line coverage remains above the 50% high-debt boundary and branch coverage clears the 70% canonical threshold; line and method coverage remain non-hard remediation debt against the canonical 80% targets. The current PHPUnit suite passes 76 tests with 643 assertions.

The remaining environment/integration proofs are:

- host-application integration proof;
- production database migration execution;
- vendor-backed remote CI/deployment proof.

The local PostgreSQL development database has been reconciled safely with the current Entity/Objecting model. The legacy `currency_currency` table contained zero rows, so the initial migration used an additive compatibility path, created `currency_translation`, established a real Doctrine migration ledger, and preserved data by refusing automatic legacy reconciliation when a non-canonical table contains rows. Follow-up migrations align PostgreSQL identity/default/index metadata exactly with Doctrine; `doctrine:schema:validate` is green, `composer schema:diff` reports nothing to update, and `/currencing/demo` returns HTTP 200 on the real standalone runtime.

## Risks to check during runtime hardening

- route import in host app;
- Twig namespace registration;
- Doctrine mapping path registration when installed as a component;
- service alias conflicts if host app overrides Currencing services;
- migration namespace/path registration;
- fixture ordering if host app has its own fixture loader.
