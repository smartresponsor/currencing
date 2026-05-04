# Currencing M15: API contract readiness

M15 adds API contract artifacts for host apps, Interfacing, tests, and automation.

## Added files

```text
docs/api/currencing.openapi.yaml
docs/api/currencing.http
docs/api/currencing-endpoints.md
delivery/release/currencing-endpoints.json
tools/currencing-api-contract-check.php
```

## Gate

Run:

```bash
php tools/currencing-api-contract-check.php
```

The gate checks that OpenAPI, HTTP examples, and endpoint manifest contain the expected
Currencing API paths and route names.

## Scope

The API contract covers:

- currency metadata read;
- selector read;
- money normalization;
- Currencing/Exchanging boundary read.

## Non-goals

M15 does not add:

- FX/rate conversion;
- write/admin CRUD;
- payment provider behavior;
- order/tax/discount business rules.
