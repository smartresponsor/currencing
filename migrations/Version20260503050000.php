<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260503050000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename initial Currencing table and indexes to currency_* convention for PostgreSQL.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Currencing M17 migrations are PostgreSQL-first and require the postgresql platform.'
        );

        if ($schema->hasTable('currency') && !$schema->hasTable('currency_currency')) {
            $this->addSql('ALTER TABLE currency RENAME TO currency_currency');
        }

        if ($schema->hasTable('currency_currency')) {
            $table = $schema->getTable('currency_currency');

            if ($table->hasIndex('uniq_currency_code') && !$table->hasIndex('uniq_currency_currency_code')) {
                $this->addSql('ALTER INDEX uniq_currency_code RENAME TO uniq_currency_currency_code');
            }

            if ($table->hasIndex('idx_currency_active') && !$table->hasIndex('idx_currency_currency_active_code')) {
                $this->addSql('ALTER INDEX idx_currency_active RENAME TO idx_currency_currency_active_code');
            }
        }
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Currencing M17 migrations are PostgreSQL-first and require the postgresql platform.'
        );

        if ($schema->hasTable('currency_currency') && !$schema->hasTable('currency')) {
            $this->addSql('ALTER TABLE currency_currency RENAME TO currency');
        }

        if ($schema->hasTable('currency')) {
            $table = $schema->getTable('currency');

            if ($table->hasIndex('uniq_currency_currency_code') && !$table->hasIndex('uniq_currency_code')) {
                $this->addSql('ALTER INDEX uniq_currency_currency_code RENAME TO uniq_currency_code');
            }

            if ($table->hasIndex('idx_currency_currency_active_code') && !$table->hasIndex('idx_currency_active')) {
                $this->addSql('ALTER INDEX idx_currency_currency_active_code RENAME TO idx_currency_active');
            }
        }
    }
}
