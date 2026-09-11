<?php

declare(strict_types=1);

use App\Currencing\Kernel;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$root = dirname(__DIR__);
(new Dotenv())->bootEnv($root.'/.env');
$errors = [];
$kernel = new Kernel('test', false);
$kernel->boot();

try {
    $registry = $kernel->getContainer()->get('doctrine');
    $manager = $registry->getManager();
    $metadata = $manager->getMetadataFactory()->getAllMetadata();

    $migrationSources = '';
    foreach (glob($root.'/migrations/*.php') ?: [] as $migration) {
        $contents = file_get_contents($migration);
        if (is_string($contents)) {
            $migrationSources .= "\n".$contents;
        }
    }

    if ('' === trim($migrationSources)) {
        $errors[] = 'No backend-owned migration source was found.';
    }

    $mappedTables = [];
    foreach ($metadata as $classMetadata) {
        if (!$classMetadata instanceof ClassMetadata || !str_starts_with($classMetadata->getName(), 'App\\Currencing\\Entity\\Currency\\')) {
            continue;
        }

        $table = $classMetadata->getTableName();
        $mappedTables[] = $table;
        if (!preg_match('/CREATE TABLE\s+'.preg_quote($table, '/').'\s*\(/i', $migrationSources)) {
            $errors[] = 'Migration mirror is missing table '.$table.'.';
            continue;
        }

        foreach ($classMetadata->fieldMappings as $mapping) {
            $column = is_array($mapping) ? ($mapping['columnName'] ?? null) : ($mapping->columnName ?? null);
            if (is_string($column) && !preg_match('/\b'.preg_quote($column, '/').'\b/i', $migrationSources)) {
                $errors[] = sprintf('Migration mirror is missing column %s.%s.', $table, $column);
            }
        }

        foreach ($classMetadata->associationMappings as $mapping) {
            $joinColumns = is_array($mapping) ? ($mapping['joinColumns'] ?? []) : ($mapping->joinColumns ?? []);
            foreach ($joinColumns as $joinColumn) {
                $column = is_array($joinColumn) ? ($joinColumn['name'] ?? null) : ($joinColumn->name ?? null);
                if (is_string($column) && !preg_match('/\b'.preg_quote($column, '/').'\b/i', $migrationSources)) {
                    $errors[] = sprintf('Migration mirror is missing association column %s.%s.', $table, $column);
                }
            }
        }
    }

    sort($mappedTables);
    if (['currency_currency', 'currency_translation'] !== $mappedTables) {
        $errors[] = 'Currencing must map exactly currency_currency and currency_translation; got: '.implode(', ', $mappedTables);
    }

    foreach (['currency_exchange', ' display_name ', 'object_'] as $forbidden) {
        if (str_contains(strtolower($migrationSources), strtolower($forbidden))) {
            $errors[] = 'Migration contains forbidden legacy/FX storage token: '.trim($forbidden);
        }
    }

    foreach (['uuid', 'slug', 'created_at', 'active', 'enabled', 'status', 'first_title'] as $requiredObjectColumn) {
        if (!str_contains($migrationSources, $requiredObjectColumn)) {
            $errors[] = 'Migration is missing canonical Objecting column '.$requiredObjectColumn.'.';
        }
    }
} finally {
    $kernel->shutdown();
}

if ([] !== $errors) {
    fwrite(STDERR, "Currencing schema/migration mirror gate failed:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - '.$error."\n");
    }
    exit(1);
}

fwrite(STDOUT, "Currencing schema/migration mirror gate passed.\n");
