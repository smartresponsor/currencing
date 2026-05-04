# Currencing command matrix

## Patch apply

```powershell
powershell -ExecutionPolicy Bypass -File "C:\Users\Admin\Downloads\apply_currencing_m20_lazyghost_var_exporter_runtime_fix_touched.ps1" `
  -ProjectRoot "D:\PhpstormProjects\www\Currencing" `
  -PatchZip "C:\Users\Admin\Downloads\currencing_m20_lazyghost_var_exporter_runtime_fix_touched.zip"
```

## Framework-free gates

```powershell
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
php tools/currencing-service-alias-closure-check.php
php tools/currencing-console-runtime-proof-check.php
php tools/currencing-api-contract-check.php
php tools/currencing-release-candidate-check.php
php tools/currencing-standalone-runtime-foundation-check.php
```

## Symfony gates

```powershell
composer update symfony/var-exporter --with-dependencies
composer dump-autoload
php bin/console cache:clear
php bin/console debug:router | findstr currencing
php bin/console debug:container App\ServiceInterface\Currency
```

## Doctrine gates

```powershell
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

## Optional browser/API proof

```text
GET  /currencing/currencies
GET  /currencing/currency-selector
POST /currencing/money/normalize
GET  /currencing/conversion-boundary
GET  /currencing/demo
GET  /currencing/admin-preview/currencies
```


## M21 database runtime proof

```powershell
php tools/currencing-database-runtime-proof-check.php
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

If `doctrine:schema:validate` fails with a PostgreSQL authentication error for user `app`, copy `.env.local.example` to `.env.local` and set a valid local PostgreSQL DSN.
