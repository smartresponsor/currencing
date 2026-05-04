# Currencing release-candidate checklist

## Must pass locally

```powershell
cd D:\PhpstormProjects\www\Currencing
composer dump-autoload
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
php tools/currencing-api-contract-check.php
php bin/console cache:clear
```

## Should pass before integration

```powershell
php bin/console debug:router | findstr currencing
php bin/console debug:container App\ServiceInterface\Currency
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

## Expected routes

```text
currencing_currency_catalog
currencing_currency_metadata
currencing_currency_selector
currencing_money_normalize
currencing_conversion_boundary
currencing_demo_index
currencing_admin_preview_currencies
```

## Expected Doctrine table

```text
currency_currency
```

## Expected config files

```text
config/services/currencing.yaml
config/packages/currencing.yaml
config/packages/doctrine_currencing.yaml
config/packages/twig.yaml
```

## Release-candidate acceptance

Currencing can be treated as RC when:

- all framework-free gates pass;
- Symfony container compiles;
- routes are visible;
- Doctrine mapping is visible;
- `currency_currency` schema is valid or migration is generated/applied;
- demo/API endpoints render in the host app.
