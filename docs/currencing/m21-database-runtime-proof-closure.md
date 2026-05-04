# M21 — Database runtime proof closure

M21 closes the first real local runtime blocker after M20. The Symfony kernel, route collection, service aliases, and Doctrine mapping loaded successfully. The remaining failure was database authentication against the placeholder `app/app` PostgreSQL DSN.

M21 does not add business scope. It adds a local PostgreSQL proof guide, a `.env.local.example`, and a framework-free database runtime proof gate.

## Local finding

Successful local proof:

- `composer update symfony/var-exporter --with-dependencies`
- `php bin/console cache:clear`
- `php bin/console debug:router | findstr currencing`
- `php bin/console debug:container App\ServiceInterface\Currency`
- `php bin/console doctrine:mapping:info`

Remaining blocker:

- `php bin/console doctrine:schema:validate` reached the database phase and failed because PostgreSQL rejected credentials for user `app`.

## Closure

Use `.env.local` for local database credentials. Keep `.env` generic and committed. Do not switch Currencing user data to SQLite.
