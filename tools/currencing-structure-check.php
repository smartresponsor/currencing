<?php

declare(strict_types=1);

/**
 * Currencing structural gate.
 *
 * This script intentionally avoids framework bootstrapping. It checks source-tree and
 * naming invariants that must hold before runtime proof starts.
 */
$root = dirname(__DIR__);
$errors = [];

$composer = file_get_contents($root.'/composer.json');
if (!is_string($composer)) {
    $errors[] = 'Cannot read composer.json.';
} else {
    foreach (['cruding/crud', 'objecting/object', 'viewing/view', 'interfacing/interface', 'easycorp/easyadmin-bundle'] as $requiredPackage) {
        if (!str_contains($composer, '"'.$requiredPackage.'"')) {
            $errors[] = 'Missing canonical backend stack dependency: '.$requiredPackage;
        }
    }
}

$forbiddenDirectories = [
    $root.'/src/Domain',
    $root.'/Domain',
    $root.'/Currency',
];

foreach ($forbiddenDirectories as $directory) {
    if (is_dir($directory)) {
        $errors[] = 'Forbidden directory exists: '.str_replace($root.'/', '', $directory);
    }
}

$requiredDirectories = [
    'src/Entity/Currency',
    'src/Repository',
    'src/DTO',
    'src/ValueObject',
    'src/Service',
    'src/ServiceInterface',
    'src/Service/Http/Currency',
    'docs/currencing',
];

foreach ($requiredDirectories as $directory) {
    if (!is_dir($root.'/'.$directory)) {
        $errors[] = 'Required directory is missing: '.$directory;
    }
}

$phpFiles = iterator_to_array(new RegexIterator(
    new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/src')),
    '/\.php$/'
));

foreach ($phpFiles as $file) {
    $path = $file->getPathname();
    $relative = str_replace($root.'/', '', str_replace('\\', '/', $path));
    $contents = file_get_contents($path);

    if (!is_string($contents)) {
        $errors[] = 'Cannot read PHP file: '.$relative;
        continue;
    }

    if (!str_contains($contents, 'namespace App\\Currencing;') && !str_contains($contents, 'namespace App\\Currencing\\')) {
        $errors[] = 'Non-canonical Currencing namespace in: '.$relative;
    }

    if (1 === preg_match('/namespace\s+App\\\\(?!Currencing(?:\\\\|;))/', $contents)) {
        $errors[] = 'Forbidden bare App namespace in: '.$relative;
    }

    if (1 === preg_match('/class\s+\w*CurrencyConverter\b|interface\s+\w*ExchangeRateProvider\b|class\s+\w*ExchangeRateProvider\b|class\s+CurrencyExchangeEntity\b|interface\s+CurrencyExchangeRepositoryInterface\b|class\s+CurrencyExchangeRepository\b/', $contents)) {
        $errors[] = 'Forbidden FX/converter responsibility inside Currencing: '.$relative;
    }
}

$currencyEntity = $root.'/src/Entity/Currency/CurrencyEntity.php';
if (is_file($currencyEntity)) {
    $contents = file_get_contents($currencyEntity);
    if (!is_string($contents) || !str_contains($contents, "name: 'currency_currency'")) {
        $errors[] = 'Currency entity must map to currency_currency.';
    }
}

$migrations = is_dir($root.'/migrations')
    ? iterator_to_array(new RegexIterator(
        new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/migrations')),
        '/\.php$/'
    ))
    : [];

foreach ($migrations as $migration) {
    $path = $migration->getPathname();
    $relative = str_replace($root.'/', '', str_replace('\\', '/', $path));
    $contents = file_get_contents($path);

    if (!is_string($contents)) {
        $errors[] = 'Cannot read migration file: '.$relative;
        continue;
    }

    if (preg_match_all("/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?([a-zA-Z0-9_]+)/i", $contents, $matches) > 0) {
        foreach ($matches[1] as $tableName) {
            if (!str_starts_with(strtolower($tableName), 'currency_')) {
                $errors[] = 'Migration creates non-currency-prefixed table '.$tableName.' in '.$relative;
            }
        }
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Currencing structural gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - '.$error."\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing structural gate passed.\n");
