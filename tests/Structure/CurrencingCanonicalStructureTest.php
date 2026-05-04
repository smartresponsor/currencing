<?php

declare(strict_types=1);

namespace App\Tests\Structure;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SplFileInfo;

final class CurrencingCanonicalStructureTest extends TestCase
{
    public function testRequiredTypeLayerDirectoriesExist(): void
    {
        foreach ([
            'src/Entity/Currency',
            'src/Repository/Currency',
            'src/Dto/Currency',
            'src/ValueObject/Currency',
            'src/Service/Currency',
            'src/ServiceInterface/Currency',
            'src/Controller/Currency',
            'docs/currencing',
        ] as $directory) {
            self::assertDirectoryExists($this->root() . '/' . $directory);
        }
    }

    public function testForbiddenLegacyDirectoriesDoNotExist(): void
    {
        foreach ([
            'src/Domain',
            'Domain',
            'Currency',
        ] as $directory) {
            self::assertDirectoryDoesNotExist($this->root() . '/' . $directory);
        }
    }

    public function testSourceFilesUseDefaultSymfonyNamespace(): void
    {
        foreach ($this->sourcePhpFiles() as $file) {
            $contents = file_get_contents($file->getPathname());

            self::assertIsString($contents);
            self::assertStringContainsString('namespace App\\', $contents, $file->getPathname());
        }
    }

    public function testCurrencyEntityUsesCanonicalTableName(): void
    {
        $contents = file_get_contents($this->root() . '/src/Entity/Currency/Currency.php');

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
     * @return list<SplFileInfo>
     */
    private function sourcePhpFiles(): array
    {
        return iterator_to_array(new RegexIterator(
            new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->root() . '/src')),
            '/\.php$/'
        ));
    }

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }
}
