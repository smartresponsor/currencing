# Currencing local PostgreSQL proof

Currencing user data is PostgreSQL-first. SQLite is not used for Currencing-owned user-data entities.

The default `.env` intentionally contains a placeholder-style PostgreSQL DSN. On a fresh workstation, `doctrine:schema:validate` can fail with an authentication error for user `app`. That is not a mapping failure; it means local PostgreSQL credentials are not configured yet.

## Option A: create a dedicated local role and database

Run in `psql` as a PostgreSQL admin user:

```sql
CREATE USER currencing WITH PASSWORD 'currencing';
CREATE DATABASE currencing OWNER currencing;
GRANT ALL PRIVILEGES ON DATABASE currencing TO currencing;
```

Then copy `.env.local.example` to `.env.local` and keep this DSN:

```dotenv
DATABASE_URL="postgresql://currencing:currencing@127.0.0.1:5432/currencing?serverVersion=16&charset=utf8"
```

## Option B: use an existing local postgres role for proof only

If your local PostgreSQL instance already uses `postgres/postgres`, copy `.env.local.example` to `.env.local` and switch to the commented postgres DSN.

## Runtime proof commands

```powershell
cd D:\PhpstormProjects\www\Currencing

php bin/console cache:clear
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:mapping:info
php bin/console doctrine:schema:validate
```

Expected mapped entity:

```text
App\Entity\Currency\Currency
```

Expected schema validation result after database credentials are correct and migrations are applied:

```text
[OK] The mapping files are correct.
[OK] The database schema is in sync with the mapping files.
```
