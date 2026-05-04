<?php

declare(strict_types=1);

namespace App\Tests\Release;

use PHPUnit\Framework\TestCase;

final class CurrencingReleaseCandidateFilesTest extends TestCase
{
    public function testReleaseCandidateFilesExist(): void
    {
        self::assertFileExists(__DIR__ . '/../../delivery/release/currencing-rc-readiness.json');
        self::assertFileExists(__DIR__ . '/../../docs/currencing/release-candidate-checklist.md');
        self::assertFileExists(__DIR__ . '/../../docs/currencing/command-matrix.md');
    }

    public function testReleaseCandidateJsonContainsStatus(): void
    {
        $contents = file_get_contents(__DIR__ . '/../../delivery/release/currencing-rc-readiness.json');

        self::assertIsString($contents);
        self::assertStringContainsString('release-candidate-architecture-business-complete', $contents);
        self::assertStringContainsString('php bin/console cache:clear', $contents);
    }
}
