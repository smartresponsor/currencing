<?php

declare(strict_types=1);

namespace DoctrineMigrations\Currencing;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260922222500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop redundant non-unique currency code index covered by uniq_currency_currency_code.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform,
            'Currencing index cleanup supports PostgreSQL only.',
        );

        $this->addSql('DROP INDEX IF EXISTS currency_idx');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE INDEX IF NOT EXISTS currency_idx ON currency_currency (code)');
    }
}
