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
            'php tools/currencing-api-contract-check.php',
            'php bin/console cache:clear',
        ] as $expectedCommand) {
            if (!is_array($commands) || !in_array($expectedCommand, $commands, true)) {
                $errors[] = 'RC readiness JSON missing command: ' . $expectedCommand;
            }
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
