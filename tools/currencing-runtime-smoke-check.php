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
    $path = $root.'/'.$relative;

    if (!is_file($path)) {
        $errors[] = 'Required file is missing: '.$relative;

        return '';
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Required file is not readable: '.$relative;

        return '';
    }

    return $contents;
}

$componentPackage = read_file_required($root, 'config/packages/currency_component.yaml', $errors);
$services = read_file_required($root, 'config/services/currency_services.yaml', $errors);
$rootServices = read_file_required($root, 'config/services.yaml', $errors);
$rootRoutes = read_file_required($root, 'config/routes.yaml', $errors);
$currencingRoutes = read_file_required($root, 'config/routes/currency_routes.yaml', $errors);
$doctrine = read_file_required($root, 'config/packages/currency_doctrine.yaml', $errors);
$twig = read_file_required($root, 'config/packages/twig.yaml', $errors);

$requiredAliases = [
    'App\\Currencing\\ServiceInterface\\CurrencyMetadataProviderInterface' => 'App\\Currencing\\Service\\CurrencyIntlMetadataProvider',
    'App\\Currencing\\ServiceInterface\\CurrencyCodeValidatorInterface' => 'App\\Currencing\\Service\\CurrencyCodeValidator',
    'App\\Currencing\\ServiceInterface\\CurrencyPrecisionResolverInterface' => 'App\\Currencing\\Service\\CurrencyPrecisionResolver',
    'App\\Currencing\\ServiceInterface\\CurrencyAmountNormalizerInterface' => 'App\\Currencing\\Service\\CurrencyCanonicalAmountNormalizer',
    'App\\Currencing\\ServiceInterface\\CurrencyAmountInputResolverInterface' => 'App\\Currencing\\Service\\CurrencyAmountInputResolver',
    'App\\Currencing\\ServiceInterface\\CurrencyDisplayFormatterInterface' => 'App\\Currencing\\Service\\CurrencyDisplayFormatter',
    'App\\Currencing\\ServiceInterface\\CurrencyRoundingPolicyResolverInterface' => 'App\\Currencing\\Service\\CurrencyRoundingPolicyResolver',
    'App\\Currencing\\ServiceInterface\\CurrencyConversionBoundaryProviderInterface' => 'App\\Currencing\\Service\\CurrencyConversionBoundaryProvider',
];

foreach ($requiredAliases as $interface => $implementation) {
    if (!str_contains($services, $interface)) {
        $errors[] = 'Missing service interface alias key: '.$interface;
    }

    if (!str_contains($services, $implementation)) {
        $errors[] = 'Missing service implementation alias target: '.$implementation;
    }
}

if ('' !== $componentPackage && str_contains($componentPackage, 'services:')) {
    $errors[] = 'config/packages/currency_component.yaml must not duplicate service aliases; aliases belong in config/services/currency_services.yaml.';
}

if ('' !== $rootServices && !str_contains($rootServices, 'services/currency_services.yaml')) {
    $errors[] = 'Root services.yaml must import config/services/currency_services.yaml.';
}

if ('' !== $rootRoutes && !str_contains($rootRoutes, 'routes/currency_routes.yaml')) {
    $errors[] = 'Root routes.yaml must import config/routes/currency_routes.yaml.';
}

if ('' !== $currencingRoutes && (!str_contains($currencingRoutes, 'currencing_money_normalize') || !str_contains($currencingRoutes, 'currencing_currency_catalog'))) {
    $errors[] = 'Currencing route map must declare canonical endpoint route names.';
}

if (!str_contains($twig, 'src/Resources/views') || !str_contains($twig, 'Currencing')) {
    $errors[] = 'Twig namespace Currencing is not configured for src/Resources/views.';
}

foreach ([
    'App\\Currencing\\Service\\Http\\Currency\\',
    'App\\Currencing\\DataFixtures\\',
    'App\\Currencing\\Form\\',
    'App\\Currencing\\Repository\\',
    'App\\Currencing\\Service\\',
    'App\\Currencing\\Validator\\',
] as $resourceNamespace) {
    if (!str_contains($services, $resourceNamespace)) {
        $errors[] = 'Missing explicit service resource namespace: '.$resourceNamespace;
    }
}

if (!str_contains($doctrine, 'src/Entity/Currency') || !str_contains($doctrine, "prefix: 'App\\Currencing\\Entity\\Currency'")) {
    $errors[] = 'Doctrine Currencing mapping must target src/Entity/Currency with App\\Currencing\\Entity\\Currency prefix.';
}

$routeExpectations = [
    '/currencing/currencies',
    'currencing_currency_catalog',
    'currencing_currency_metadata',
    '/currencing/currency-selector',
    'currencing_currency_selector',
    '/currencing/template-context',
    'currencing_template_context',
    '/currencing/money/normalize',
    'currencing_money_normalize',
    '/currencing/demo',
    'currencing_demo_index',
    '/currencing/admin-preview/currencies',
    'currencing_admin_preview_currencies',
    '/currencing/conversion-boundary',
    'currencing_conversion_boundary',
];

foreach ($routeExpectations as $needle) {
    if ('' !== $currencingRoutes && !str_contains($currencingRoutes, $needle)) {
        $errors[] = 'Route expectation missing in config/routes/currency_routes.yaml: '.$needle;
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

$entity = read_file_required($root, 'src/Entity/Currency/CurrencyEntity.php', $errors);
if ('' !== $entity && !str_contains($entity, 'repositoryClass: CurrencyRepository::class')) {
    $errors[] = 'Currency entity should reference CurrencyRepository::class.';
}

if ([] !== $errors) {
    fwrite(STDERR, "Currencing runtime smoke gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - '.$error."\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing runtime smoke gate passed.\n");
