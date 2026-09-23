<?php

declare(strict_types=1);

namespace DoctrineMigrations\Currencing;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260923230500 extends AbstractMigration
{
    /** @var array<string, string> */
    private const array INDEX_RENAMES = [
        'uniq_593ede2cd17f50a6' => 'uniq_currency_currency_uuid',
        'uniq_593ede2c989d9b62' => 'uniq_currency_currency_slug',
        'uniq_5814bced17f50a6' => 'uniq_currency_translation_uuid',
        'uniq_5814bce989d9b62' => 'uniq_currency_translation_slug',
    ];

    public function getDescription(): string
    {
        return 'Rename Currencing Objecting identity unique indexes to deterministic semantic names.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform,
            'Currencing identity index naming migration supports PostgreSQL only.',
        );

        foreach (self::INDEX_RENAMES as $legacy => $canonical) {
            $this->addSql(sprintf(
                <<<'SQL'
DO $$
BEGIN
    IF to_regclass('public.%1$s') IS NOT NULL AND to_regclass('public.%2$s') IS NULL THEN
        EXECUTE 'ALTER INDEX %1$s RENAME TO %2$s';
    ELSIF to_regclass('public.%1$s') IS NOT NULL AND to_regclass('public.%2$s') IS NOT NULL THEN
        RAISE EXCEPTION 'Both legacy index %1$s and canonical index %2$s exist; manual reconciliation is required.';
    END IF;
END
$$
SQL,
                $legacy,
                $canonical,
            ));
        }
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException(
            'Deterministic Currencing Objecting identity constraint naming is intentionally irreversible.',
        );
    }
}
