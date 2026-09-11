<?php

declare(strict_types=1);

namespace DoctrineMigrations\Currencing;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260905234900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Currencing entity schema with canonical Objecting system-field packs.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE currency_currency (id SERIAL NOT NULL, code VARCHAR(3) NOT NULL, numeric_code VARCHAR(3) DEFAULT NULL, minor_unit SMALLINT NOT NULL, symbol VARCHAR(16) DEFAULT NULL, uuid BYTEA NOT NULL, slug VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_by VARCHAR(190) DEFAULT NULL, modified_by VARCHAR(190) DEFAULT NULL, active BOOLEAN NOT NULL, enabled BOOLEAN NOT NULL, status VARCHAR(64) DEFAULT NULL, first_title VARCHAR(255) DEFAULT NULL, middle_title TEXT DEFAULT NULL, last_title TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CURRENCY_UUID ON currency_currency (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CURRENCY_SLUG ON currency_currency (slug)');
        $this->addSql('CREATE UNIQUE INDEX uniq_currency_currency_code ON currency_currency (code)');
        $this->addSql('CREATE INDEX idx_currency_currency_active_code ON currency_currency (active, code)');
        $this->addSql('CREATE INDEX currency_idx ON currency_currency (code)');

        $this->addSql('CREATE TABLE currency_translation (id SERIAL NOT NULL, currency_id INT NOT NULL, symbol VARCHAR(16) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, uuid BYTEA NOT NULL, slug VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_by VARCHAR(190) DEFAULT NULL, modified_by VARCHAR(190) DEFAULT NULL, locale VARCHAR(16) NOT NULL, timezone VARCHAR(64) NOT NULL, active BOOLEAN NOT NULL, enabled BOOLEAN NOT NULL, status VARCHAR(64) DEFAULT NULL, first_title VARCHAR(255) DEFAULT NULL, middle_title TEXT DEFAULT NULL, last_title TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CURRENCY_TRANSLATION_UUID ON currency_translation (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CURRENCY_TRANSLATION_SLUG ON currency_translation (slug)');
        $this->addSql('CREATE INDEX IDX_CURRENCY_TRANSLATION_CURRENCY ON currency_translation (currency_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_currency_translation_currency_locale ON currency_translation (currency_id, locale)');
        $this->addSql('CREATE INDEX idx_currency_translation_locale ON currency_translation (locale)');
        $this->addSql('ALTER TABLE currency_translation ADD CONSTRAINT FK_CURRENCY_TRANSLATION_CURRENCY FOREIGN KEY (currency_id) REFERENCES currency_currency (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE currency_translation');
        $this->addSql('DROP TABLE currency_currency');
    }
}
