<?php

declare(strict_types=1);

/**
 * Currencing console runtime proof gate.
 *
 * This static gate checks the files that must be coherent before the first
 * local Symfony bin/console proof commands are expected to run. It does not
 * boot Symfony and does not require vendor/.
 */

$root = dirname(__DIR__);
$errors = [];

function m19_read_required(string $root, string $relative, array &$errors): string
{
    $path = $root . '/' . $relative;

    if (!is_file($path)) {
        $errors[] = 'Missing console-runtime file: ' . $relative;

        return '';
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Unreadable console-runtime file: ' . $relative;

        return '';
    }

    return $contents;
}

$composer = m19_read_required($root, 'composer.json', $errors);
if ($composer !== '') {
    $decoded = json_decode($composer, true);
    if (!is_array($decoded)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        foreach ([
            'php',
            'doctrine/doctrine-bundle',
            'doctrine/doctrine-migrations-bundle',
            'doctrine/orm',
            'symfony/console',
            'symfony/dotenv',
            'symfony/framework-bundle',
            'symfony/runtime',
            'symfony/twig-bundle',
            'symfony/validator',
            'symfony/var-exporter',
            'symfony/yaml',
            'twig/twig',
        ] as $package) {
            if (!isset($decoded['require'][$package])) {
                $errors[] = 'composer.json missing required runtime package: ' . $package;
            }
        }

        if (($decoded['autoload']['psr-4']['App\\'] ?? null) !== 'src/') {
            $errors[] = 'composer.json must autoload App\\ from src/.';
        }

        $gateScript = $decoded['scripts']['currencing:gates'] ?? [];
        foreach ([
            'php tools/currencing-structure-check.php',
            'php tools/currencing-runtime-smoke-check.php',
            'php tools/currencing-autoload-smoke-check.php',
            'php tools/currencing-service-alias-closure-check.php',
            'php tools/currencing-console-runtime-proof-check.php',
            'php tools/currencing-api-contract-check.php',
            'php tools/currencing-release-candidate-check.php',
            'php tools/currencing-standalone-runtime-foundation-check.php',
        ] as $expectedGate) {
            if (!is_array($gateScript) || !in_array($expectedGate, $gateScript, true)) {
                $errors[] = 'composer currencing:gates missing command: ' . $expectedGate;
            }
        }
    }
}

$console = m19_read_required($root, 'bin/console', $errors);
foreach ([
    "require_once dirname(__DIR__) . '/vendor/autoload_runtime.php'",
    'use App\\Kernel;',
    'return static function (array $context): Application',
] as $needle) {
    if ($console !== '' && !str_contains($console, $needle)) {
        $errors[] = 'bin/console missing Symfony runtime marker: ' . $needle;
    }
}

$frontController = m19_read_required($root, 'public/index.php', $errors);
foreach ([
    "require_once dirname(__DIR__) . '/vendor/autoload_runtime.php'",
    'use App\\Kernel;',
    'return static function (array $context): Kernel',
] as $needle) {
    if ($frontController !== '' && !str_contains($frontController, $needle)) {
        $errors[] = 'public/index.php missing Symfony runtime marker: ' . $needle;
    }
}

$kernel = m19_read_required($root, 'src/Kernel.php', $errors);
foreach (['namespace App;', 'MicroKernelTrait', 'final class Kernel extends BaseKernel'] as $needle) {
    if ($kernel !== '' && !str_contains($kernel, $needle)) {
        $errors[] = 'src/Kernel.php missing marker: ' . $needle;
    }
}

$env = m19_read_required($root, '.env', $errors);
foreach (['APP_ENV=dev', 'APP_SECRET=', 'DATABASE_URL="postgresql://'] as $needle) {
    if ($env !== '' && !str_contains($env, $needle)) {
        $errors[] = '.env missing local proof marker: ' . $needle;
    }
}

$framework = m19_read_required($root, 'config/packages/framework.yaml', $errors);
foreach (['secret:', 'handle_all_throwables: true', 'when@test:', 'storage_factory_id: session.storage.factory.mock_file'] as $needle) {
    if ($framework !== '' && !str_contains($framework, $needle)) {
        $errors[] = 'config/packages/framework.yaml missing marker: ' . $needle;
    }
}

$twig = m19_read_required($root, 'config/packages/twig.yaml', $errors);
if ($twig !== '' && !str_contains($twig, "'%kernel.project_dir%/src/Resources/views': Currencing")) {
    $errors[] = 'config/packages/twig.yaml must register the @Currencing template namespace.';
}

$routes = m19_read_required($root, 'config/routes.yaml', $errors);
$currencyRoutes = m19_read_required($root, 'config/routes/currencing.yaml', $errors);
if ($routes !== '' && !str_contains($routes, 'routes/currencing.yaml')) {
    $errors[] = 'config/routes.yaml must import routes/currencing.yaml.';
}
if ($currencyRoutes !== '' && (!str_contains($currencyRoutes, 'resource: ../../src/Controller/Currency/') || !str_contains($currencyRoutes, 'type: attribute'))) {
    $errors[] = 'config/routes/currencing.yaml must import src/Controller/Currency attribute routes.';
}

$doctrine = m19_read_required($root, 'config/packages/doctrine.yaml', $errors);
foreach (['DATABASE_URL', "server_version: '16'", 'auto_mapping: false'] as $needle) {
    if ($doctrine !== '' && !str_contains($doctrine, $needle)) {
        $errors[] = 'config/packages/doctrine.yaml missing marker: ' . $needle;
    }
}

$mapping = m19_read_required($root, 'config/packages/doctrine_currencing.yaml', $errors);
foreach (["dir: '%kernel.project_dir%/src/Entity/Currency'", "prefix: 'App\\Entity\\Currency'", 'alias: Currencing'] as $needle) {
    if ($mapping !== '' && !str_contains($mapping, $needle)) {
        $errors[] = 'config/packages/doctrine_currencing.yaml missing marker: ' . $needle;
    }
}

$services = m19_read_required($root, 'config/services.yaml', $errors);
$currencyServices = m19_read_required($root, 'config/services/currencing.yaml', $errors);
if ($services !== '' && !str_contains($services, 'services/currencing.yaml')) {
    $errors[] = 'config/services.yaml must import services/currencing.yaml.';
}
foreach (['App\\Controller\\Currency\\:', 'controller.service_arguments', 'App\\ServiceInterface\\Currency\\CurrencyMetadataProviderInterface'] as $needle) {
    if ($currencyServices !== '' && !str_contains($currencyServices, $needle)) {
        $errors[] = 'config/services/currencing.yaml missing marker: ' . $needle;
    }
}

foreach ([
    '@Currencing/layout.html.twig' => 'src/Resources/views/layout.html.twig',
    '@Currencing/currency/demo/index.html.twig' => 'src/Resources/views/currency/demo/index.html.twig',
    '@Currencing/currency/admin-preview/currencies.html.twig' => 'src/Resources/views/currency/admin-preview/currencies.html.twig',
] as $templateName => $relative) {
    if (!is_file($root . '/' . $relative)) {
        $errors[] = 'Missing template for runtime proof: ' . $templateName . ' at ' . $relative;
    }
}

if ($errors !== []) {
    fwrite(STDERR, "Currencing console runtime proof gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing console runtime proof gate passed.\n");
