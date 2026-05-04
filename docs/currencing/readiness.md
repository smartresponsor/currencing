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

## Not part of current readiness percentage

These are final hardening/proof tasks, not architectural/business-completeness blockers:

- host-app runtime proof;
- browser proof;
- real database migration execution;
- full PHPUnit execution in target environment;
- PHPStan/Psalm level proof;
- vendor-backed CI;
- final cache/runtime cleanup.

## Risks to check during runtime hardening

- route import in host app;
- Twig namespace registration;
- Doctrine mapping path registration when installed as a component;
- service alias conflicts if host app overrides Currencing services;
- migration namespace/path registration;
- fixture ordering if host app has its own fixture loader.
