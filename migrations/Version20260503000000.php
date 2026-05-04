<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260503000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create currency metadata table for Currencing component.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE currency_currency (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, numeric_code VARCHAR(3) DEFAULT NULL, minor_unit SMALLINT NOT NULL, symbol VARCHAR(16) DEFAULT NULL, display_name VARCHAR(128) DEFAULT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX uniq_currency_currency_code (code), INDEX idx_currency_currency_active_code (active, code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE currency_currency');
    }
}
