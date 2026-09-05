# M22 — Template bridge output contract

## Purpose

M22 adds an explicit outbound Currencing contract for Bridge/Interfacing/templates composition.
The goal is to let a bridge connect Currencing to UI/template systems without coupling
Currencing to a specific renderer, Twig markup, FormView object, frontend package, or Bridge
implementation.

## Added contract

```text
src/ServiceInterface/Currency/CurrencyTemplateContextProviderInterface.php
```

Primary method:

```text
context(?string $selectedCode = null, ?string $locale = null): CurrencyTemplateContextDTO
```

Bridge-side consumers should depend on this interface when integration is in-process.

## Added DTO/read model

```text
src/Dto/Currency/CurrencyTemplateContextDTO.php
```

The context contains:

- component key: `currencing.currency`
- selected currency code
- optional locale
- selector view DTO
- metadata view DTO list
- route names for bridge URL resolution
- capability list for UI composition

It does not expose Doctrine entities, Symfony forms, Twig templates, HTTP responses, or
Bridge-specific classes.

## Added provider

```text
src/Service/Currency/CurrencyTemplateContextProvider.php
```

The provider composes existing selector and metadata view providers. It does not add a new
business responsibility; it only packages existing output into a bridge-safe context.

## Added HTTP read surface

```text
GET /currencing/template-context
route: currencing_template_context
```

This endpoint is for HTTP-style bridge reads. For in-process Symfony composition, prefer
`CurrencyTemplateContextProviderInterface`.

## Gate

```text
php tools/currencing-template-bridge-contract-check.php
```

The gate verifies the contract, DTO, provider, controller, service alias, API contract, and
endpoint manifest. It also guards against coupling the output contract to Doctrine entities,
Twig, FormView, Interfacing classes, or Bridge classes.
