<?php

declare(strict_types=1);

$root = dirname(__DIR__);

function currencing_database_runtime_fail(string $message): never
{
    fwrite(STDERR, "Currencing database runtime proof gate failed: {$message}" . PHP_EOL);
    exit(1);
}

function currencing_database_runtime_read(string $path): string
{
    if (!is_file($path)) {
        currencing_database_runtime_fail("Missing required file: " . str_replace(dirname(__DIR__) . DIRECTORY_SEPARATOR, '', $path));
    }

    $contents = file_get_contents($path);
    if ($contents === false) {
        currencing_database_runtime_fail("Cannot read file: " . $path);
    }

    return $contents;
}

$env = currencing_database_runtime_read($root . '/.env');
$envExample = currencing_database_runtime_read($root . '/.env.local.example');
$doctrine = currencing_database_runtime_read($root . '/config/packages/doctrine.yaml');
$commandMatrix = currencing_database_runtime_read($root . '/docs/currencing/command-matrix.md');
$localProof = currencing_database_runtime_read($root . '/docs/currencing/local-postgresql-proof.md');

if (!str_contains($env, 'DATABASE_URL="postgresql://')) {
    currencing_database_runtime_fail('.env must keep a PostgreSQL DATABASE_URL placeholder for user-data proof.');
}

if (!str_contains($env, 'serverVersion=16')) {
    currencing_database_runtime_fail('.env DATABASE_URL must declare PostgreSQL serverVersion=16.');
}

if (!str_contains($envExample, 'postgresql://currencing:currencing@127.0.0.1:5432/currencing')) {
    currencing_database_runtime_fail('.env.local.example must include the dedicated currencing local PostgreSQL role/database DSN.');
}

if (!str_contains($envExample, 'postgresql://postgres:postgres@127.0.0.1:5432/currencing')) {
    currencing_database_runtime_fail('.env.local.example must include the optional postgres-superuser local proof DSN.');
}

foreach (["url: '%env(resolve:DATABASE_URL)%'", "server_version: '16'", 'use_savepoints: true'] as $needle) {
    if (!str_contains($doctrine, $needle)) {
        currencing_database_runtime_fail("Doctrine DBAL config must contain: {$needle}");
    }
}

foreach ([
    'php bin/console doctrine:database:create --if-not-exists',
    'php bin/console doctrine:migrations:migrate --no-interaction',
    'php bin/console doctrine:schema:validate',
] as $needle) {
    if (!str_contains($commandMatrix, $needle) && !str_contains($localProof, $needle)) {
        currencing_database_runtime_fail("Database proof docs must contain command: {$needle}");
    }
}

if (!str_contains($localProof, 'CREATE USER currencing WITH PASSWORD')) {
    currencing_database_runtime_fail('Local PostgreSQL proof docs must include the dedicated role creation command.');
}

if (is_file($root . '/.env.local')) {
    $local = currencing_database_runtime_read($root . '/.env.local');
    if (str_contains($local, 'sqlite://')) {
        currencing_database_runtime_fail('.env.local must not switch Currencing user data to SQLite. PostgreSQL is required for user data.');
    }
}

echo 'Currencing database runtime proof gate passed.' . PHP_EOL;
