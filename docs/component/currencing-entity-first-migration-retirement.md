# Currencing entity and migration ownership

## Scope

Currencing is entity-first, but entity-first does not mean migration-free. Doctrine entity metadata is the design source for the component model, while Currencing owns forward migrations that materialize that design in backend databases.

## Canonical schema ownership

- `CurrencyEntity` owns `currency_currency`.
- `CurrencyTranslationEntity` owns `currency_translation`.
- Currencing owns its Doctrine primary key `id`; `ObjectIdentityEmbeddableTrait` supplies reusable Objecting identity fields without replacing the consumer primary key.
- Objecting supplies universal system-field semantics through logical `object_*` field packs, while the physical Currencing columns remain flat and entity-native (`id`, `uuid`, `slug`, `created_at`, `active`, and so on).
- Local compatibility methods such as `isActive()` and `getDisplayName()` may remain, but they map to Objecting state/title storage instead of duplicate `active` or `display_name` columns.

## Migration mirror

`migrations/**` is a required backend-owned projection of the entity design. The migration namespace is `DoctrineMigrations\Currencing`, which allows the Host application to register Currencing migrations alongside its own `DoctrineMigrations` path without namespace collisions.

`tools/currencing-schema-migration-mirror-check.php` boots Doctrine metadata and verifies that mapped Currencing tables/columns are represented by the migration surface.

## Responsibility boundary

`CurrencyExchangeEntity`, exchange-rate repositories, and a `currency_exchange` table do not belong to Currencing. Exchange-rate sourcing and persistence belong to Exchanging. Currencing owns currency identity, metadata, precision, formatting, normalization, and conversion-boundary validation only.

## Validation

The release gate chain includes structural checks, Doctrine runtime checks, the entity/migration mirror check, API contracts, and standalone runtime proof. Full PHPUnit is also required before release.
