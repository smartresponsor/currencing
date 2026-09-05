# Currencing entity-first migration retirement

## Scope

This patch converts the Currencing component away from schema-first migration ownership.
The component keeps its runtime `Currency` entity and adds the missing legacy concepts from the old monolith as canonical PHP entities.

## Retired schema-first files

- `Currencing/migrations/**`

These files must no longer be the source of truth for the component schema at this stage.

## Existing entity-first coverage

- `Currency` already covered the former `currency_currency` table and kept the runtime `active + code` query path used by current services.

## Restored from old monolith

- `CurrencyEnUs` was normalized into `CurrencyTranslationEntity`.
- `CurrencyExchange` was restored as `CurrencyExchangeEntity`.
- The old `CategoryFeatured` class under `Entity/Currency` was not imported as a Currencing entity because it is a category/featured concept, not a currency aggregate.

## Objecting adoption

New restored entities use Objecting embeddable traits for system identity, audit, locale/source and state fields.
Currency's existing `active` column remains for current query compatibility and because it is already part of the public selector/metadata service contract.

## Validation performed

PHP syntax lint was executed for changed PHP files. Full Doctrine metadata validation was not executed because the isolated slice does not include the runtime host/vendor installation.
