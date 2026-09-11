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
    'src/Entity/Currency/CurrencyEntity.php' => 'App\\Currencing\\Entity\\Currency\\CurrencyEntity',
    'src/Repository/CurrencyRepository.php' => 'App\\Currencing\\Repository\\CurrencyRepository',
    'src/Service/CurrencyAmountInputResolver.php' => 'App\\Currencing\\Service\\CurrencyAmountInputResolver',
    'src/Service/CurrencyRoundingPolicyResolver.php' => 'App\\Currencing\\Service\\CurrencyRoundingPolicyResolver',
    'src/Service/CurrencyConversionBoundaryProvider.php' => 'App\\Currencing\\Service\\CurrencyConversionBoundaryProvider',
    'src/Service/CurrencyTemplateContextProvider.php' => 'App\\Currencing\\Service\\CurrencyTemplateContextProvider',
    'src/DTO/CurrencyTemplateContextDTO.php' => 'App\\Currencing\\DTO\\CurrencyTemplateContextDTO',
    'src/Service/Http/Currency/CurrencyTemplateContextHttpService.php' => 'App\\Currencing\\Service\\Http\\Currency\\CurrencyTemplateContextHttpService',
    'src/Service/Http/Currency/CurrencyNormalizeHttpService.php' => 'App\\Currencing\\Service\\Http\\Currency\\CurrencyNormalizeHttpService',
];

foreach ($expectedClasses as $relative => $fqcn) {
    $path = $root.'/'.$relative;

    if (!is_file($path)) {
        $errors[] = 'Expected class file is missing: '.$relative;
        continue;
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Expected class file is unreadable: '.$relative;
        continue;
    }

    $namespace = substr($fqcn, 0, strrpos($fqcn, '\\'));
    $shortName = substr($fqcn, strrpos($fqcn, '\\') + 1);

    if (!str_contains($contents, 'namespace '.$namespace.';')) {
        $errors[] = 'Namespace mismatch for '.$fqcn.' in '.$relative;
    }

    if (!preg_match('/(?:class|interface|enum)\s+'.preg_quote($shortName, '/').'\b/', $contents)) {
        $errors[] = 'Class/interface/enum declaration mismatch for '.$fqcn.' in '.$relative;
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Currencing autoload smoke gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - '.$error."\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing autoload smoke gate passed.\n");
