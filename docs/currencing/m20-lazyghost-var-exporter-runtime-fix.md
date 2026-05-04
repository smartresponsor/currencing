# M20 LazyGhost VarExporter runtime fix

## Scope

M20 is a narrow local-runtime fix wave. It does not add Currencing business scope.

## Problem

Local `composer install` and every `php bin/console ...` command failed during Symfony cache boot with:

```text
Symfony LazyGhost is not available. Please install the "symfony/var-exporter" package version 6.4 or 7 to use this feature or enable PHP 8.4 native lazy objects.
```

The repository config enables Doctrine lazy ghost objects through `doctrine.orm.enable_lazy_ghost_objects: true`, but the standalone runtime foundation did not require Symfony VarExporter.

## Fix

`composer.json` now explicitly requires:

```json
"symfony/var-exporter": "^7.3"
```

The static runtime gates now check this dependency before `bin/console` proof.

## Local proof sequence

```powershell
composer update symfony/var-exporter --with-dependencies
composer dump-autoload
php tools/currencing-console-runtime-proof-check.php
php tools/currencing-release-candidate-check.php
php bin/console cache:clear
php bin/console debug:router | findstr currencing
php bin/console debug:container App\ServiceInterface\Currency
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

## Boundary

This wave only closes the Doctrine/Symfony lazy-object runtime dependency gap. It does not change entities, DTOs, controllers, API contracts, routes, or business behavior.
