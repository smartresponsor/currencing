# M19 Console Runtime Proof Gate

M19 adds a framework-free gate for the last static checks before local Symfony `bin/console` proof.

## Scope

No new business capability was added. This milestone only strengthens runtime-readiness proof for the existing Currencing RC surface.

## Added gate

```bash
php tools/currencing-console-runtime-proof-check.php
```

The gate validates:

- Composer runtime package coverage;
- PSR-4 `App\\` autoloading;
- `currencing:gates` command registration;
- `bin/console` and `public/index.php` Symfony Runtime markers;
- default `App\\Kernel` with `MicroKernelTrait`;
- `.env` local proof markers with PostgreSQL `DATABASE_URL`;
- route imports for Currencing controllers;
- service imports and representative aliases;
- Doctrine DBAL/ORM mapping for `src/Entity/Currency`;
- Twig `@Currencing` namespace and required demo/admin templates.

## Local proof sequence

```powershell
cd D:\PhpstormProjects\www\Currencing

composer install
composer dump-autoload

php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
php tools/currencing-service-alias-closure-check.php
php tools/currencing-console-runtime-proof-check.php
php tools/currencing-api-contract-check.php
php tools/currencing-release-candidate-check.php
php tools/currencing-standalone-runtime-foundation-check.php

php bin/console cache:clear
php bin/console debug:router | findstr currencing
php bin/console debug:container App\ServiceInterface\Currency
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

## RC meaning

After M19, remaining RC proof is vendor-backed/local: Composer dependency resolution, Symfony container boot, router visibility, container aliases, Doctrine mapping, and schema validation.
