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

## M17 standalone runtime foundation gate

Run:

```bash
php tools/currencing-standalone-runtime-foundation-check.php
```

This gate checks the default Symfony App\... runtime foundation, route imports, deduplicated config, and PostgreSQL-first migration markers without booting the Symfony container.

- `currencing-service-alias-closure-check.php` verifies constructor-injected Currencing service interfaces, explicit Symfony aliases, and implementation/interface alignment without booting Symfony.

## M19 console runtime proof gate

Run:

```bash
php tools/currencing-console-runtime-proof-check.php
```

This gate checks Composer runtime package coverage, Symfony runtime entrypoints, Kernel, `.env`, route imports, service imports, Doctrine mapping, Twig namespace, and required templates without requiring `vendor/`.

## M20 LazyGhost runtime dependency proof

`php tools/currencing-console-runtime-proof-check.php` now also verifies that `composer.json` requires `symfony/var-exporter`. Doctrine ORM needs Symfony VarExporter LazyGhost support when `doctrine.orm.enable_lazy_ghost_objects` is enabled and PHP native lazy objects are not being used by the running stack.


- `currencing-database-runtime-proof-check.php` — verifies PostgreSQL-first local runtime proof files, DSN templates, Doctrine DBAL wiring, and database proof command documentation.
