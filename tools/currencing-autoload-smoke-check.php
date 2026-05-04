<?php

declare(strict_types=1);

/**
 * Currencing autoload smoke gate.
 *
 * This script validates source declarations and class/file relationships without requiring
 * Composer's generated autoloader to be present.
 */

$root = dirname(__DIR__);
$errors = [];

$expectedClasses = [
    'src/Entity/Currency/Currency.php' => 'App\\Entity\\Currency\\Currency',
    'src/Repository/Currency/CurrencyRepository.php' => 'App\\Repository\\Currency\\CurrencyRepository',
    'src/Service/Currency/MonetaryAmountInputResolver.php' => 'App\\Service\\Currency\\MonetaryAmountInputResolver',
    'src/Service/Currency/MoneyRoundingPolicyResolver.php' => 'App\\Service\\Currency\\MoneyRoundingPolicyResolver',
    'src/Service/Currency/CurrencyConversionBoundaryProvider.php' => 'App\\Service\\Currency\\CurrencyConversionBoundaryProvider',
    'src/Controller/Currency/MoneyNormalizeController.php' => 'App\\Controller\\Currency\\MoneyNormalizeController',
];

foreach ($expectedClasses as $relative => $fqcn) {
    $path = $root . '/' . $relative;

    if (!is_file($path)) {
        $errors[] = 'Expected class file is missing: ' . $relative;
        continue;
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Expected class file is unreadable: ' . $relative;
        continue;
    }

    $namespace = substr($fqcn, 0, strrpos($fqcn, '\\'));
    $shortName = substr($fqcn, strrpos($fqcn, '\\') + 1);

    if (!str_contains($contents, 'namespace ' . $namespace . ';')) {
        $errors[] = 'Namespace mismatch for ' . $fqcn . ' in ' . $relative;
    }

    if (!preg_match('/(?:class|interface|enum)\s+' . preg_quote($shortName, '/') . '\b/', $contents)) {
        $errors[] = 'Class/interface/enum declaration mismatch for ' . $fqcn . ' in ' . $relative;
    }
}

if ($errors !== []) {
    fwrite(STDERR, "Currencing autoload smoke gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing autoload smoke gate passed.\n");
