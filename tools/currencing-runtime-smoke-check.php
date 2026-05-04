<?php

declare(strict_types=1);

/**
 * Currencing runtime smoke gate.
 *
 * This script does not boot Symfony. It checks compile-risk configuration and naming
 * consistency before a host app/container proof run.
 */

$root = dirname(__DIR__);
$errors = [];

function read_file_required(string $root, string $relative, array &$errors): string
{
    $path = $root . '/' . $relative;

    if (!is_file($path)) {
        $errors[] = 'Required file is missing: ' . $relative;

        return '';
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Required file is not readable: ' . $relative;

        return '';
    }

    return $contents;
}

$services = read_file_required($root, 'config/packages/currencing.yaml', $errors);
$serviceResources = read_file_required($root, 'config/services/currencing.yaml', $errors);
$doctrine = read_file_required($root, 'config/packages/doctrine_currencing.yaml', $errors);
$twig = read_file_required($root, 'config/packages/twig.yaml', $errors);

$requiredAliases = [
    'App\\ServiceInterface\\Currency\\CurrencyMetadataProviderInterface' => 'App\\Service\\Currency\\IntlCurrencyMetadataProvider',
    'App\\ServiceInterface\\Currency\\CurrencyCodeValidatorInterface' => 'App\\Service\\Currency\\CurrencyCodeValidator',
    'App\\ServiceInterface\\Currency\\CurrencyPrecisionResolverInterface' => 'App\\Service\\Currency\\CurrencyPrecisionResolver',
    'App\\ServiceInterface\\Currency\\MoneyAmountNormalizerInterface' => 'App\\Service\\Currency\\CanonicalMoneyAmountNormalizer',
    'App\\ServiceInterface\\Currency\\MonetaryAmountInputResolverInterface' => 'App\\Service\\Currency\\MonetaryAmountInputResolver',
    'App\\ServiceInterface\\Currency\\MoneyDisplayFormatterInterface' => 'App\\Service\\Currency\\MoneyDisplayFormatter',
    'App\\ServiceInterface\\Currency\\MoneyRoundingPolicyResolverInterface' => 'App\\Service\\Currency\\MoneyRoundingPolicyResolver',
    'App\\ServiceInterface\\Currency\\CurrencyConversionBoundaryProviderInterface' => 'App\\Service\\Currency\\CurrencyConversionBoundaryProvider',
];

foreach ($requiredAliases as $interface => $implementation) {
    if (!str_contains($services, $interface)) {
        $errors[] = 'Missing service interface alias key: ' . $interface;
    }

    if (!str_contains($services, $implementation)) {
        $errors[] = 'Missing service implementation alias target: ' . $implementation;
    }
}

if (!str_contains($twig, 'src/Resources/views') || !str_contains($twig, 'Currencing')) {
    $errors[] = 'Twig namespace Currencing is not configured for src/Resources/views.';
}

foreach ([
    'App\\Controller\\Currency\\',
    'App\\DataFixtures\\Currency\\',
    'App\\Form\\Currency\\',
    'App\\Repository\\Currency\\',
    'App\\Service\\Currency\\',
    'App\\Validator\\Currency\\',
] as $resourceNamespace) {
    if (!str_contains($serviceResources, $resourceNamespace)) {
        $errors[] = 'Missing explicit service resource namespace: ' . $resourceNamespace;
    }
}

if (!str_contains($doctrine, 'src/Entity/Currency') || !str_contains($doctrine, "prefix: 'App\\Entity\\Currency'")) {
    $errors[] = 'Doctrine Currencing mapping must target src/Entity/Currency with App\\Entity\\Currency prefix.';
}


$routeExpectations = [
    'src/Controller/Currency/CurrencyMetadataController.php' => [
        '/currencing/currencies',
        "name: 'currencing_currency_'",
        "name: 'catalog'",
        "name: 'metadata'",
    ],
    'src/Controller/Currency/CurrencySelectorController.php' => [
        '/currencing/currency-selector',
        'currencing_currency_selector',
    ],
    'src/Controller/Currency/MoneyNormalizeController.php' => [
        '/currencing/money/normalize',
        'currencing_money_normalize',
    ],
    'src/Controller/Currency/CurrencyDemoController.php' => [
        '/currencing/demo',
        'currencing_demo_index',
    ],
    'src/Controller/Currency/CurrencyAdminPreviewController.php' => [
        '/currencing/admin-preview/currencies',
        'currencing_admin_preview_currencies',
    ],
    'src/Controller/Currency/CurrencyConversionBoundaryController.php' => [
        '/currencing/conversion-boundary',
        'currencing_conversion_boundary',
    ],
];

foreach ($routeExpectations as $relative => $needles) {
    $contents = read_file_required($root, $relative, $errors);

    foreach ($needles as $needle) {
        if ($contents !== '' && !str_contains($contents, $needle)) {
            $errors[] = 'Route expectation missing in ' . $relative . ': ' . $needle;
        }
    }
}

$templateExpectations = [
    'src/Resources/views/layout.html.twig',
    'src/Resources/views/currency/demo/index.html.twig',
    'src/Resources/views/currency/admin-preview/currencies.html.twig',
];

foreach ($templateExpectations as $relative) {
    read_file_required($root, $relative, $errors);
}

$entity = read_file_required($root, 'src/Entity/Currency/Currency.php', $errors);
if ($entity !== '' && !str_contains($entity, "repositoryClass: CurrencyRepository::class")) {
    $errors[] = 'Currency entity should reference CurrencyRepository::class.';
}

if ($errors !== []) {
    fwrite(STDERR, "Currencing runtime smoke gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing runtime smoke gate passed.\n");
