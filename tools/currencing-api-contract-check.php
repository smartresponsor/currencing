<?php

declare(strict_types=1);

/**
 * Currencing API contract gate.
 *
 * The gate keeps API docs, endpoint manifest, and controller route declarations aligned
 * without requiring Symfony to boot.
 */

$root = dirname(__DIR__);
$errors = [];

function must_read(string $root, string $relative, array &$errors): string
{
    $path = $root . '/' . $relative;
    if (!is_file($path)) {
        $errors[] = 'Missing required API contract file: ' . $relative;

        return '';
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Unreadable API contract file: ' . $relative;

        return '';
    }

    return $contents;
}

$openapi = must_read($root, 'docs/api/currencing.openapi.yaml', $errors);
$http = must_read($root, 'docs/api/currencing.http', $errors);
$manifestContents = must_read($root, 'delivery/release/currencing-endpoints.json', $errors);

$expectedPaths = [
    '/currencing/currencies',
    '/currencing/currencies/{code}',
    '/currencing/currency-selector',
    '/currencing/money/normalize',
    '/currencing/conversion-boundary',
];

foreach ($expectedPaths as $path) {
    if ($openapi !== '' && !str_contains($openapi, $path . ':')) {
        $errors[] = 'OpenAPI contract is missing path: ' . $path;
    }

    $httpPath = str_replace('{code}', 'USD', $path);
    if ($http !== '' && !str_contains($http, $httpPath)) {
        $errors[] = 'HTTP examples are missing path: ' . $httpPath;
    }

    if ($manifestContents !== '' && !str_contains($manifestContents, $path)) {
        $errors[] = 'Endpoint manifest is missing path: ' . $path;
    }
}

$expectedRoutes = [
    'currencing_currency_catalog',
    'currencing_currency_metadata',
    'currencing_currency_selector',
    'currencing_money_normalize',
    'currencing_conversion_boundary',
];

foreach ($expectedRoutes as $route) {
    if ($manifestContents !== '' && !str_contains($manifestContents, $route)) {
        $errors[] = 'Endpoint manifest is missing route: ' . $route;
    }
}

if ($manifestContents !== '') {
    $decoded = json_decode($manifestContents, true);
    if (!is_array($decoded)) {
        $errors[] = 'Endpoint manifest is not valid JSON.';
    }
}

if ($errors !== []) {
    fwrite(STDERR, "Currencing API contract gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing API contract gate passed.\n");
