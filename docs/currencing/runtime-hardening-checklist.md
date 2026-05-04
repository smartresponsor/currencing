# Currencing runtime hardening checklist

Run after applying the latest touched-files patch.

## Local component commands

```bash
composer dump-autoload
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
php tools/currencing-api-contract-check.php
php tools/currencing-release-candidate-check.php
php bin/console cache:clear
php bin/console debug:container "App\ServiceInterface\Currency"
php bin/console debug:router | grep currencing
```

## Doctrine checks

```bash
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

Expected canonical table:

```text
currency_currency
```

## Twig checks

Expected namespace:

```text
@Currencing
```

Expected templates:

```text
@Currencing/layout.html.twig
@Currencing/currency/demo/index.html.twig
@Currencing/currency/admin-preview/currencies.html.twig
```

## Expected routes

```text
currencing_currency_catalog
currencing_currency_metadata
currencing_currency_selector
currencing_money_normalize
currencing_demo_index
currencing_admin_preview_currencies
currencing_conversion_boundary
```

## Host app note

If Currencing is symlinked or mounted into another Symfony application, clear the host
application cache after applying the patch.


## Config files added for explicit host-app readiness

```text
config/services/currencing.yaml
config/packages/doctrine_currencing.yaml
```
