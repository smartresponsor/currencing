# Currencing M13: Runtime/container hardening pass

M13 adds pre-runtime smoke checks for likely Symfony container and presentation wiring risks.

## Script

```bash
php tools/currencing-runtime-smoke-check.php
```

## What it checks

The smoke gate verifies:

- critical service interface aliases exist in `config/packages/currencing.yaml`;
- Twig namespace `Currencing` points to `src/Resources/views`;
- expected routes are present in controller attributes;
- M9 demo templates exist;
- `Currency` entity references `CurrencyRepository::class`.

## What it does not do

This gate does not boot Symfony and does not replace:

- `composer dump-autoload`;
- `php bin/console cache:clear`;
- `php bin/console debug:container`;
- `php bin/console debug:router`;
- database migration execution.

It is intentionally framework-free so it can run even before vendor/runtime is fully ready.
