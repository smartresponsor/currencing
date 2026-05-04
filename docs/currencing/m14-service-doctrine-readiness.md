# Currencing M14: Service/autowiring and Doctrine mapping readiness

M14 adds explicit Symfony configuration and framework-free smoke checks that reduce
host-app ambiguity before real container compilation.

## Added config

```text
config/services/currencing.yaml
config/packages/doctrine_currencing.yaml
```

## Service readiness

`config/services/currencing.yaml` explicitly registers Currencing type layers:

- controllers;
- fixtures;
- forms;
- repositories;
- services;
- validators;
- service interface aliases.

## Doctrine readiness

`config/packages/doctrine_currencing.yaml` maps:

```text
dir: src/Entity/Currency
prefix: App\Entity\Currency
alias: Currencing
```

Canonical table remains:

```text
currency_currency
```

## Added smoke gate

```bash
php tools/currencing-autoload-smoke-check.php
```

Use together:

```bash
php tools/currencing-structure-check.php
php tools/currencing-runtime-smoke-check.php
php tools/currencing-autoload-smoke-check.php
```

## Runtime proof still separate

M14 does not replace real Symfony commands:

```bash
composer dump-autoload
php bin/console cache:clear
php bin/console debug:container
php bin/console debug:router
php bin/console doctrine:mapping:info
```
