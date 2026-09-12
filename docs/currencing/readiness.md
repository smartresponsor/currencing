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

Local repository proof is green for the component-owned RC gates, PHPUnit, PHPStan, coding-style checks, and strict Composer validation.

The remaining environment/integration proofs are intentionally outside the component-local acceptance boundary:

- host-application integration proof;
- browser/UI proof in the assembled product;
- production database migration execution;
- vendor-backed remote CI/deployment proof.

## Risks to check during runtime hardening

- route import in host app;
- Twig namespace registration;
- Doctrine mapping path registration when installed as a component;
- service alias conflicts if host app overrides Currencing services;
- migration namespace/path registration;
- fixture ordering if host app has its own fixture loader.
