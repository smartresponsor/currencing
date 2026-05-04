<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260503050000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename initial Currencing table and indexes to currency_* convention.';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('currency') && !$schema->hasTable('currency_currency')) {
            $this->addSql('RENAME TABLE currency TO currency_currency');
        }

        if ($schema->hasTable('currency_currency')) {
            $table = $schema->getTable('currency_currency');

            if ($table->hasIndex('uniq_currency_code')) {
                $this->addSql('ALTER TABLE currency_currency DROP INDEX uniq_currency_code, ADD UNIQUE INDEX uniq_currency_currency_code (code)');
            }

            if ($table->hasIndex('idx_currency_active')) {
                $this->addSql('ALTER TABLE currency_currency DROP INDEX idx_currency_active, ADD INDEX idx_currency_currency_active_code (active, code)');
            }
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('currency_currency') && !$schema->hasTable('currency')) {
            $this->addSql('RENAME TABLE currency_currency TO currency');
        }
    }
}
