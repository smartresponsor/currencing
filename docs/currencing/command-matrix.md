# Currencing command matrix

## Patch apply

```powershell
powershell -ExecutionPolicy Bypass -File "C:\Users\Admin\Downloads\apply_currencing_m16_release_candidate_closure_touched.ps1" `
  -ProjectRoot "D:\PhpstormProjects\www\Currencing" `
  -PatchZip "C:\Users\Admin\Downloads\currencing_m16_release_candidate_closure_touched.zip"
```

## Framework-free gates

```powershell
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
php tools/currencing-api-contract-check.php
```

## Symfony gates

```powershell
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
