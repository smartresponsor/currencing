<?php

declare(strict_types=1);

/**
 * Currencing service alias closure gate.
 *
 * This framework-free gate scans constructor-injected Currencing service
 * interfaces and verifies that every App\ServiceInterface\Currency contract
 * has an explicit alias to an existing App\Service\Currency implementation.
 */

$root = dirname(__DIR__);
$errors = [];

function m18_read_required(string $root, string $relative, array &$errors): string
{
    $path = $root . '/' . $relative;

    if (!is_file($path)) {
        $errors[] = 'Required file is missing: ' . $relative;

        return '';
    }

    $contents = file_get_contents($path);
    if (!is_string($contents)) {
        $errors[] = 'Required file is unreadable: ' . $relative;

        return '';
    }

    return $contents;
}

function m18_fqcn_to_path(string $root, string $fqcn): string
{
    if (!str_starts_with($fqcn, 'App\\')) {
        return '';
    }

    return $root . '/src/' . str_replace('\\', '/', substr($fqcn, 4)) . '.php';
}

$services = m18_read_required($root, 'config/services/currencing.yaml', $errors);
$componentPackage = m18_read_required($root, 'config/packages/currencing.yaml', $errors);

if ($componentPackage !== '' && str_contains($componentPackage, 'services:')) {
    $errors[] = 'config/packages/currencing.yaml must not contain service definitions or aliases.';
}

preg_match_all(
    '/^\s{2}(App\\\\ServiceInterface\\\\Currency\\\\[A-Za-z0-9_]+Interface):\s*\n\s{4}alias:\s*(App\\\\Service\\\\Currency\\\\[A-Za-z0-9_]+)/m',
    $services,
    $aliasMatches,
    PREG_SET_ORDER
);

$aliases = [];
foreach ($aliasMatches as $match) {
    $interface = str_replace('\\\\', '\\', $match[1]);
    $implementation = str_replace('\\\\', '\\', $match[2]);

    if (isset($aliases[$interface])) {
        $errors[] = 'Duplicate service alias for interface: ' . $interface;
    }

    $aliases[$interface] = $implementation;
}

$injectedInterfaces = [];
$sourceIterator = new RegexIterator(
    new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src')),
    '/\.php$/'
);

foreach ($sourceIterator as $file) {
    $relative = str_replace($root . '/', '', str_replace('\\', '/', $file->getPathname()));
    $contents = file_get_contents($file->getPathname());

    if (!is_string($contents)) {
        $errors[] = 'Cannot read source file: ' . $relative;
        continue;
    }

    preg_match_all('/use\s+(App\\\\ServiceInterface\\\\Currency\\\\([A-Za-z0-9_]+Interface));/', $contents, $useMatches, PREG_SET_ORDER);

    foreach ($useMatches as $useMatch) {
        $fqcn = str_replace('\\\\', '\\', $useMatch[1]);
        $short = $useMatch[2];

        if (preg_match('/(?:public|protected|private)?\s*(?:readonly\s+)?' . preg_quote($short, '/') . '\s+\$[A-Za-z0-9_]+/', $contents) === 1) {
            $injectedInterfaces[$fqcn][] = $relative;
        }
    }
}

foreach ($injectedInterfaces as $interface => $files) {
    if (!isset($aliases[$interface])) {
        $errors[] = 'Missing service alias for injected interface ' . $interface . ' used by ' . implode(', ', $files);
        continue;
    }

    $interfacePath = m18_fqcn_to_path($root, $interface);
    if ($interfacePath === '' || !is_file($interfacePath)) {
        $errors[] = 'Injected interface file is missing for ' . $interface;
    }
}

foreach ($aliases as $interface => $implementation) {
    $interfacePath = m18_fqcn_to_path($root, $interface);
    $implementationPath = m18_fqcn_to_path($root, $implementation);

    if ($interfacePath === '' || !is_file($interfacePath)) {
        $errors[] = 'Alias interface file is missing: ' . $interface;
        continue;
    }

    if ($implementationPath === '' || !is_file($implementationPath)) {
        $errors[] = 'Alias implementation file is missing: ' . $implementation;
        continue;
    }

    $interfaceContents = (string) file_get_contents($interfacePath);
    $implementationContents = (string) file_get_contents($implementationPath);
    $interfaceShort = substr($interface, strrpos($interface, '\\') + 1);
    $implementationShort = substr($implementation, strrpos($implementation, '\\') + 1);

    if (!str_contains($interfaceContents, 'interface ' . $interfaceShort)) {
        $errors[] = 'Alias interface declaration mismatch: ' . $interface;
    }

    if (!preg_match('/(?:final\s+)?(?:readonly\s+)?class\s+' . preg_quote($implementationShort, '/') . '\b/', $implementationContents)) {
        $errors[] = 'Alias implementation class declaration mismatch: ' . $implementation;
    }

    if (!str_contains($implementationContents, 'implements ' . $interfaceShort)) {
        $errors[] = 'Alias implementation does not explicitly implement ' . $interfaceShort . ': ' . $implementation;
    }
}

if ($errors !== []) {
    fwrite(STDERR, "Currencing service alias closure gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Currencing service alias closure gate passed.\n");
