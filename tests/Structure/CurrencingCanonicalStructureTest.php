<?php

declare(strict_types=1);

namespace App\Currencing\Tests\Structure;

use PHPUnit\Framework\TestCase;

final class CurrencingCanonicalStructureTest extends TestCase
{
    public function testRequiredTypeLayerDirectoriesExist(): void
    {
        foreach ([
            'src/Entity/Currency',
            'src/Repository',
            'src/DTO',
            'src/ValueObject',
            'src/Service',
            'src/ServiceInterface',
            'src/Service/Http/Currency',
            'docs/currencing',
        ] as $directory) {
            self::assertDirectoryExists($this->root().'/'.$directory);
        }
    }

    public function testForbiddenLegacyDirectoriesDoNotExist(): void
    {
        foreach ([
            'src/Domain',
            'Domain',
            'Currency',
        ] as $directory) {
            self::assertDirectoryDoesNotExist($this->root().'/'.$directory);
        }
    }

    public function testSourceFilesUseDefaultSymfonyNamespace(): void
    {
        foreach ($this->sourcePhpFiles() as $file) {
            $contents = file_get_contents($file->getPathname());

            self::assertIsString($contents);
            self::assertMatchesRegularExpression('/namespace App(?:\\\\[^;]+)?;/', $contents, $file->getPathname());
        }
    }

    public function testCanonicalTypeSuffixesAreEnforced(): void
    {
        foreach (glob($this->root().'/src/DTO/*.php') ?: [] as $file) {
            self::assertStringEndsWith('DTO.php', $file, $file);
        }

        foreach (glob($this->root().'/src/Entity/Currency/*.php') ?: [] as $file) {
            self::assertStringEndsWith('Entity.php', $file, $file);
        }
    }

    public function testCurrencySubtreeTypesUseCurrencyPrefix(): void
    {
        foreach ($this->sourcePhpFiles() as $file) {
            $normalizedPath = str_replace('\\', '/', $file->getPathname());

            if (!str_contains($normalizedPath, '/Currency/')) {
                continue;
            }

            self::assertStringStartsWith(
                'Currency',
                $file->getBasename('.php'),
                $file->getPathname(),
            );
        }
    }

    public function testCurrencyEntityUsesCanonicalTableName(): void
    {
        $contents = file_get_contents($this->root().'/src/Entity/Currency/CurrencyEntity.php');

        self::assertIsString($contents);
        self::assertStringContainsString("name: 'currency_currency'", $contents);
    }

    public function testCurrencingDoesNotOwnExchangeRateProviderOrLiveConverter(): void
    {
        foreach ($this->sourcePhpFiles() as $file) {
            $contents = file_get_contents($file->getPathname());

            self::assertIsString($contents);
            self::assertDoesNotMatchRegularExpression('/class\s+\w*CurrencyConverter\b/', $contents, $file->getPathname());
            self::assertDoesNotMatchRegularExpression('/interface\s+\w*ExchangeRateProvider\b/', $contents, $file->getPathname());
            self::assertDoesNotMatchRegularExpression('/class\s+\w*ExchangeRateProvider\b/', $contents, $file->getPathname());
        }
    }

    /**
     * @return list<\SplFileInfo>
     */
    private function sourcePhpFiles(): array
    {
        return iterator_to_array(new \RegexIterator(
            new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root().'/src')),
            '/\.php$/'
        ));
    }

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }
}
