<?php

declare(strict_types=1);

/**
 * Currencing release-candidate metadata gate.
 *
 * This gate checks that RC closure artifacts exist and reference the required local
 * proof commands. It does not boot Symfony.
 */

$root = dirname(__DIR__);
$errors = [];

$requiredFiles = [
    'delivery/release/currencing-rc-readiness.json',
    'docs/currencing/m16-release-candidate-closure.md',
    'docs/currencing/release-candidate-checklist.md',
    'docs/currencing/command-matrix.md',
    'docs/currencing/m17-runtime-rc-proof-foundation.md',
    'tools/currencing-standalone-runtime-foundation-check.php',
    'tools/currencing-service-alias-closure-check.php',
    'docs/currencing/m18-service-alias-closure-gate.md',
    'tools/currencing-console-runtime-proof-check.php',
    'docs/currencing/m19-console-runtime-proof-gate.md',
    'docs/currencing/m20-lazyghost-var-exporter-runtime-fix.md',
    'tools/currencing-database-runtime-proof-check.php',
    'docs/currencing/m21-database-runtime-proof-closure.md',
    'docs/currencing/local-postgresql-proof.md',
    '.env.local.example',
];

foreach ($requiredFiles as $relative) {
    if (!is_file($root . '/' . $relative)) {
        $errors[] = 'Missing RC artifact: ' . $relative;
    }
}

$readinessPath = $root . '/delivery/release/currencing-rc-readiness.json';
if (is_file($readinessPath)) {
    $decoded = json_decode((string) file_get_contents($readinessPath), true);

    if (!is_array($decoded)) {
        $errors[] = 'RC readiness JSON is invalid.';
    } else {
        if (($decoded['component'] ?? null) !== 'Currencing') {
            $errors[] = 'RC readiness component must be Currencing.';
        }

        if (($decoded['status'] ?? null) !== 'release-candidate-architecture-business-complete') {
            $errors[] = 'Unexpected RC readiness status.';
        }

        $commands = $decoded['requiredLocalProofCommands'] ?? [];
        foreach ([
            'php tools/currencing-structure-check.php',
            'php tools/currencing-runtime-smoke-check.php',
            'php tools/currencing-autoload-smoke-check.php',
            'php tools/currencing-service-alias-closure-check.php',
            'php tools/currencing-console-runtime-proof-check.php',
            'php tools/currencing-database-runtime-proof-check.php',
            'php tools/currencing-api-contract-check.php',
            'php tools/currencing-standalone-runtime-foundation-check.php',
            'php bin/console cache:clear',
        ] as $expectedCommand) {
            if (!is_array($commands) || !in_array($expectedCommand, $commands, true)) {
                $errors[] = 'RC readiness JSON missing command: ' . $expectedCommand;
            }
        }
    }
}


$composerPath = $root . '/composer.json';
if (is_file($composerPath)) {
    $composer = json_decode((string) file_get_contents($composerPath), true);
    if (!is_array($composer) || !isset($composer['require']['symfony/var-exporter'])) {
        $errors[] = 'composer.json must require symfony/var-exporter for Doctrine LazyGhost runtime compatibility.';
    }
}


$envLocalExamplePath = $root . '/.env.local.example';
if (is_file($envLocalExamplePath)) {
    $envLocalExample = (string) file_get_contents($envLocalExamplePath);
    foreach ([
        'postgresql://currencing:currencing@127.0.0.1:5432/currencing',
        'postgresql://postgres:postgres@127.0.0.1:5432/currencing',
    ] as $needle) {
        if (!str_contains($envLocalExample, $needle)) {
            $errors[] = '.env.local.example missing local PostgreSQL proof DSN marker: ' . $needle;
        }
    }
}

$localPostgresqlProofPath = $root . '/docs/currencing/local-postgresql-proof.md';
if (is_file($localPostgresqlProofPath)) {
    $localPostgresqlProof = (string) file_get_contents($localPostgresqlProofPath);
    foreach ([
        'CREATE USER currencing WITH PASSWORD',
        'php bin/console doctrine:database:create --if-not-exists',
        'php bin/console doctrine:migrations:migrate --no-interaction',
        'php bin/console doctrine:schema:validate',
    ] as $needle) {
        if (!str_contains($localPostgresqlProof, $needle)) {
            $errors[] = 'local PostgreSQL proof doc missing marker: ' . $needle;
        }
    }
}

if ($errors !== []) {
    fwrite(STDERR, "Currencing release-candidate gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing release-candidate gate passed.\n");
