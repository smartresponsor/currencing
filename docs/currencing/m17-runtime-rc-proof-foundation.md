# M17 Runtime RC Proof Foundation

M17 moves Currencing from an architecture/business-complete component slice toward a runnable default-Symfony repository proof.

## Scope

This wave does not add new business responsibility. It hardens the runtime surface required to prove the existing Currencing responsibilities in a local Symfony application:

- default `App\...` Symfony application foundation;
- explicit controller route import for `src/Controller/Currency`;
- explicit service import through `config/services.yaml`;
- deduplicated component package configuration;
- PostgreSQL-first Doctrine migrations;
- standalone runtime foundation gate.

## Canonical runtime commands

```bash
composer install
composer dump-autoload
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
php tools/currencing-api-contract-check.php
php tools/currencing-release-candidate-check.php
php tools/currencing-standalone-runtime-foundation-check.php
php bin/console cache:clear
php bin/console debug:router | grep currencing
php bin/console debug:container 'App\ServiceInterface\Currency'
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

PowerShell equivalent for route filtering:

```powershell
php bin/console debug:router | findstr currencing
```

## PostgreSQL-first decision

Currencing-owned Doctrine tables remain prefixed with `currency_`. The canonical table for the ISO currency catalog is `currency_currency`.

M17 removes MySQL-specific migration syntax such as `AUTO_INCREMENT`, `TINYINT`, `ENGINE = InnoDB`, and `RENAME TABLE`.

## Remaining proof boundary

M17 is still a file/config hardening wave. The final RC proof requires running the commands above in the user's local repository after applying the touched-files patch.
