<?php

declare(strict_types=1);

/**
 * Currencing template bridge contract gate.
 *
 * Keeps the outbound UI/templating bridge contract explicit and decoupled from
 * Doctrine entities, Twig templates, Symfony FormView objects, or Bridge-specific code.
 */
$root = dirname(__DIR__);
$errors = [];

function m22_read_required(string $root, string $relative, array &$errors): string
{
    $path = $root.'/'.$relative;

    if (!is_file($path)) {
        $errors[] = 'Required bridge contract file is missing: '.$relative;

        return '';
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Required bridge contract file is unreadable: '.$relative;

        return '';
    }

    return $contents;
}

$contextDto = m22_read_required($root, 'src/DTO/CurrencyTemplateContextDTO.php', $errors);
$contextInterface = m22_read_required($root, 'src/ServiceInterface/CurrencyTemplateContextProviderInterface.php', $errors);
$contextProvider = m22_read_required($root, 'src/Service/CurrencyTemplateContextProvider.php', $errors);
$contextController = m22_read_required($root, 'src/Service/Http/Currency/CurrencyTemplateContextHttpService.php', $errors);
$routes = m22_read_required($root, 'config/routes/currencing.yaml', $errors);
$services = m22_read_required($root, 'config/services/currencing.yaml', $errors);
$apiDocs = m22_read_required($root, 'docs/api/currencing.openapi.yaml', $errors);
$endpointManifest = m22_read_required($root, 'delivery/release/currencing-endpoints.json', $errors);

$requiredNeedles = [
    'src/DTO/CurrencyTemplateContextDTO.php' => [
        'final readonly class CurrencyTemplateContextDTO',
        'CurrencySelectorViewDTO',
        'CurrencyMetadataViewDTO',
        'componentKey',
        'routeNames',
        'capabilities',
        'toArray',
    ],
    'src/ServiceInterface/CurrencyTemplateContextProviderInterface.php' => [
        'interface CurrencyTemplateContextProviderInterface',
        'public function context(?string $selectedCode = null, ?string $locale = null): CurrencyTemplateContextDTO',
    ],
    'src/Service/CurrencyTemplateContextProvider.php' => [
        'final class CurrencyTemplateContextProvider implements CurrencyTemplateContextProviderInterface',
        'CurrencySelectorViewProviderInterface',
        'CurrencyMetadataViewProviderInterface',
        'new CurrencyTemplateContextDTO',
    ],
    'src/Service/Http/Currency/CurrencyTemplateContextHttpService.php' => [
        'CurrencyTemplateContextProviderInterface',
        'currency_template_context',
    ],
];

$contentsByFile = [
    'src/DTO/CurrencyTemplateContextDTO.php' => $contextDto,
    'src/ServiceInterface/CurrencyTemplateContextProviderInterface.php' => $contextInterface,
    'src/Service/CurrencyTemplateContextProvider.php' => $contextProvider,
    'src/Service/Http/Currency/CurrencyTemplateContextHttpService.php' => $contextController,
];

foreach ($requiredNeedles as $relative => $needles) {
    foreach ($needles as $needle) {
        if (($contentsByFile[$relative] ?? '') !== '' && !str_contains($contentsByFile[$relative], $needle)) {
            $errors[] = 'Bridge contract expectation missing in '.$relative.': '.$needle;
        }
    }
}

if ('' !== $services && !str_contains($services, 'App\\Currencing\\ServiceInterface\\CurrencyTemplateContextProviderInterface')) {
    $errors[] = 'Missing service alias for CurrencyTemplateContextProviderInterface.';
}

foreach (['/currencing/template-context', 'currencing_template_context'] as $needle) {
    if ('' !== $routes && !str_contains($routes, $needle)) {
        $errors[] = 'Route map is missing bridge template context item: '.$needle;
    }

    if ('' !== $apiDocs && !str_contains($apiDocs, $needle)) {
        $errors[] = 'OpenAPI contract is missing bridge template context item: '.$needle;
    }

    if ('' !== $endpointManifest && !str_contains($endpointManifest, $needle)) {
        $errors[] = 'Endpoint manifest is missing bridge template context item: '.$needle;
    }
}

foreach ([$contextDto, $contextInterface, $contextProvider] as $contents) {
    foreach (['App\\Currencing\\Entity\\Currency', 'FormView', 'Twig', 'Interfacing', 'Bridge\\'] as $forbidden) {
        if ('' !== $contents && str_contains($contents, $forbidden)) {
            $errors[] = 'Bridge output contract must not couple to '.$forbidden.'.';
        }
    }
}

if ([] !== $errors) {
    fwrite(STDERR, "Currencing template bridge contract gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - '.$error."\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing template bridge contract gate passed.\n");
