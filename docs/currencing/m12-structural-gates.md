# Currencing M12: Structural gates

M12 adds framework-free structural checks for the Currencing component.

## Script

```bash
php tools/currencing-structure-check.php
```

## Checks

The gate verifies:

- `App\...` source namespace;
- required Symfony-oriented type-layer directories;
- forbidden legacy directories:
  - `src/Domain`;
  - root `Domain`;
  - root `Currency`;
- canonical Doctrine table name:
  - `currency_currency`;
- `currency_` prefix for migration-created tables;
- absence of live FX/converter responsibility inside Currencing.

## Purpose

This is not runtime proof. It is a structural guard before runtime hardening. It protects
the component from drifting back to legacy shape or absorbing Exchanging responsibilities.
