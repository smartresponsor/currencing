# Currencing tools

## Structural gate

Run from the repository root:

```bash
php tools/currencing-structure-check.php
```

The gate checks:

- canonical `App\...` namespace;
- forbidden legacy directories;
- required Symfony-oriented type layers;
- forbidden FX/converter responsibility inside Currencing;
- canonical `currency_currency` entity table;
- `currency_` prefix for migration-created tables.
## Runtime smoke gate

Run from the repository root:

```bash
php tools/currencing-runtime-smoke-check.php
```

The gate checks service aliases, Twig namespace wiring, route attribute names/paths,
template presence, and the Currency entity repository reference.


## Autoload smoke gate

Run from the repository root:

```bash
php tools/currencing-autoload-smoke-check.php
```

The gate checks selected source files for expected namespace and class/interface/enum
declarations.


## API contract gate

Run from the repository root:

```bash
php tools/currencing-api-contract-check.php
```

The gate checks OpenAPI, HTTP examples, and endpoint manifest alignment.


## Release-candidate metadata gate

Run from the repository root:

```bash
php tools/currencing-release-candidate-check.php
```

The gate checks RC closure files and required local proof commands.
