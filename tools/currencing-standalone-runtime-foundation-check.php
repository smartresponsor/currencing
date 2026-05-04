<?php

declare(strict_types=1);

/**
 * Currencing standalone runtime foundation gate.
 *
 * This gate keeps the repository RC-ready as a default-Symfony App\... project
 * without booting Composer or the Symfony container.
 */

$root = dirname(__DIR__);
$errors = [];

function m17_read_required(string $root, string $relative, array &$errors): string
{
    $path = $root . '/' . $relative;

    if (!is_file($path)) {
        $errors[] = 'Required runtime foundation file is missing: ' . $relative;

        return '';
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Runtime foundation file is not readable: ' . $relative;

        return '';
    }

    return $contents;
}

$composer = m17_read_required($root, 'composer.json', $errors);
foreach ([
    '"php": "^8.4"',
    '"App\\\\": "src/"',
    'symfony/framework-bundle',
    'doctrine/doctrine-bundle',
    'doctrine/orm',
    'symfony/twig-bundle',
    'symfony/validator',
    'symfony/var-exporter',
] as $needle) {
    if ($composer !== '' && !str_contains($composer, $needle)) {
        $errors[] = 'composer.json missing runtime requirement/autoload marker: ' . $needle;
    }
}

$kernel = m17_read_required($root, 'src/Kernel.php', $errors);
if ($kernel !== '' && (!str_contains($kernel, 'namespace App;') || !str_contains($kernel, 'MicroKernelTrait'))) {
    $errors[] = 'src/Kernel.php must be a default Symfony App\\ Kernel using MicroKernelTrait.';
}

$bundles = m17_read_required($root, 'config/bundles.php', $errors);
foreach ([
    'FrameworkBundle::class',
    'TwigBundle::class',
    'DoctrineBundle::class',
    'DoctrineMigrationsBundle::class',
] as $needle) {
    if ($bundles !== '' && !str_contains($bundles, $needle)) {
        $errors[] = 'config/bundles.php missing bundle marker: ' . $needle;
    }
}

$routes = m17_read_required($root, 'config/routes/currencing.yaml', $errors);
if ($routes !== '' && (!str_contains($routes, 'src/Controller/Currency') || !str_contains($routes, 'type: attribute'))) {
    $errors[] = 'config/routes/currencing.yaml must import Currency controllers as attribute routes.';
}

$rootRoutes = m17_read_required($root, 'config/routes.yaml', $errors);
if ($rootRoutes !== '' && !str_contains($rootRoutes, 'routes/currencing.yaml')) {
    $errors[] = 'config/routes.yaml must import routes/currencing.yaml.';
}

$services = m17_read_required($root, 'config/services.yaml', $errors);
$aliasGate = m17_read_required($root, 'tools/currencing-service-alias-closure-check.php', $errors);
$consoleProofGate = m17_read_required($root, 'tools/currencing-console-runtime-proof-check.php', $errors);
$databaseProofGate = m17_read_required($root, 'tools/currencing-database-runtime-proof-check.php', $errors);
$envLocalExample = m17_read_required($root, '.env.local.example', $errors);
if ($services !== '' && !str_contains($services, 'services/currencing.yaml')) {
    $errors[] = 'config/services.yaml must import services/currencing.yaml.';
}

if ($aliasGate !== '' && !str_contains($aliasGate, 'Currencing service alias closure gate')) {
    $errors[] = 'tools/currencing-service-alias-closure-check.php must contain the M18 service alias closure gate.';
}

if ($consoleProofGate !== '' && !str_contains($consoleProofGate, 'Currencing console runtime proof gate')) {
    $errors[] = 'tools/currencing-console-runtime-proof-check.php must contain the M19 console runtime proof gate.';
}

if ($databaseProofGate !== '' && !str_contains($databaseProofGate, 'Currencing database runtime proof gate')) {
    $errors[] = 'tools/currencing-database-runtime-proof-check.php must contain the M21 database runtime proof gate.';
}

if ($envLocalExample !== '' && !str_contains($envLocalExample, 'postgresql://currencing:currencing@127.0.0.1:5432/currencing')) {
    $errors[] = '.env.local.example must provide a dedicated local PostgreSQL proof DSN.';
}

$componentPackage = m17_read_required($root, 'config/packages/currencing.yaml', $errors);
if ($componentPackage !== '' && str_contains($componentPackage, 'services:')) {
    $errors[] = 'config/packages/currencing.yaml must not duplicate service aliases; use config/services/currencing.yaml.';
}

$doctrine = m17_read_required($root, 'config/packages/doctrine.yaml', $errors);
if ($doctrine !== '' && (!str_contains($doctrine, 'DATABASE_URL') || !str_contains($doctrine, 'auto_mapping: false'))) {
    $errors[] = 'config/packages/doctrine.yaml must define DATABASE_URL DBAL config and keep auto_mapping disabled.';
}

$doctrineMapping = m17_read_required($root, 'config/packages/doctrine_currencing.yaml', $errors);
if ($doctrineMapping !== '' && (!str_contains($doctrineMapping, 'src/Entity/Currency') || !str_contains($doctrineMapping, "prefix: 'App\\Entity\\Currency'"))) {
    $errors[] = 'config/packages/doctrine_currencing.yaml must map src/Entity/Currency to App\\Entity\\Currency.';
}

foreach (['migrations/Version20260503000000.php', 'migrations/Version20260503050000.php'] as $migration) {
    $contents = m17_read_required($root, $migration, $errors);

    foreach (['postgresql', 'currency_currency'] as $needle) {
        if ($contents !== '' && !str_contains($contents, $needle)) {
            $errors[] = $migration . ' missing PostgreSQL-safe marker: ' . $needle;
        }
    }

    if (str_ends_with($migration, '050000.php') && $contents !== '' && !str_contains($contents, 'ALTER TABLE')) {
        $errors[] = $migration . ' missing PostgreSQL table rename marker: ALTER TABLE';
    }

    foreach (['AUTO_INCREMENT', 'TINYINT', 'ENGINE = InnoDB', 'utf8mb4', 'RENAME TABLE', 'DROP INDEX'] as $forbidden) {
        if ($contents !== '' && str_contains($contents, $forbidden)) {
            $errors[] = $migration . ' contains non-PostgreSQL migration syntax: ' . $forbidden;
        }
    }
}

if ($errors !== []) {
    fwrite(STDERR, "Currencing standalone runtime foundation gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing standalone runtime foundation gate passed.\n");
