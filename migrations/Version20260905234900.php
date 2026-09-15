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
        $this->addSql("DO $$ BEGIN IF to_regclass('public.currency_currency') IS NOT NULL THEN IF (NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'currency_currency' AND column_name = 'uuid') OR NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'currency_currency' AND column_name = 'slug') OR NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'currency_currency' AND column_name = 'created_at')) THEN IF EXISTS (SELECT 1 FROM currency_currency LIMIT 1) THEN RAISE EXCEPTION 'Refusing automatic Currencing legacy-schema reconciliation because currency_currency contains data.'; END IF; END IF; END IF; END $$");

        $this->addSql('CREATE TABLE IF NOT EXISTS currency_currency (id SERIAL NOT NULL, code VARCHAR(3) NOT NULL, numeric_code VARCHAR(3) DEFAULT NULL, minor_unit SMALLINT NOT NULL, symbol VARCHAR(16) DEFAULT NULL, uuid BYTEA NOT NULL, slug VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_by VARCHAR(190) DEFAULT NULL, modified_by VARCHAR(190) DEFAULT NULL, active BOOLEAN NOT NULL, enabled BOOLEAN NOT NULL, status VARCHAR(64) DEFAULT NULL, first_title VARCHAR(255) DEFAULT NULL, middle_title TEXT DEFAULT NULL, last_title TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql("DO $$ BEGIN IF EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'currency_currency' AND column_name = 'display_name') AND NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = 'public' AND table_name = 'currency_currency' AND column_name = 'first_title') THEN EXECUTE 'ALTER TABLE currency_currency RENAME COLUMN \"display_name\" TO first_title'; END IF; END $$");
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS uuid BYTEA');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS slug VARCHAR(190)');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS created_at TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS created_by VARCHAR(190) DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS modified_by VARCHAR(190) DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS enabled BOOLEAN');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS status VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS first_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS middle_title TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_currency ADD COLUMN IF NOT EXISTS last_title TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE currency_currency ALTER COLUMN uuid SET NOT NULL');
        $this->addSql('ALTER TABLE currency_currency ALTER COLUMN slug SET NOT NULL');
        $this->addSql('ALTER TABLE currency_currency ALTER COLUMN created_at SET NOT NULL');
        $this->addSql('ALTER TABLE currency_currency ALTER COLUMN enabled SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_CURRENCY_UUID ON currency_currency (uuid)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_CURRENCY_SLUG ON currency_currency (slug)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS uniq_currency_currency_code ON currency_currency (code)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_currency_currency_active_code ON currency_currency (active, code)');
        $this->addSql('CREATE INDEX IF NOT EXISTS currency_idx ON currency_currency (code)');

        $this->addSql('CREATE TABLE IF NOT EXISTS currency_translation (id SERIAL NOT NULL, currency_id INT NOT NULL, symbol VARCHAR(16) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, uuid BYTEA NOT NULL, slug VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_by VARCHAR(190) DEFAULT NULL, modified_by VARCHAR(190) DEFAULT NULL, locale VARCHAR(16) NOT NULL, timezone VARCHAR(64) NOT NULL, active BOOLEAN NOT NULL, enabled BOOLEAN NOT NULL, status VARCHAR(64) DEFAULT NULL, first_title VARCHAR(255) DEFAULT NULL, middle_title TEXT DEFAULT NULL, last_title TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_CURRENCY_TRANSLATION_UUID ON currency_translation (uuid)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_CURRENCY_TRANSLATION_SLUG ON currency_translation (slug)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_CURRENCY_TRANSLATION_CURRENCY ON currency_translation (currency_id)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS uniq_currency_translation_currency_locale ON currency_translation (currency_id, locale)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_currency_translation_locale ON currency_translation (locale)');
        $this->addSql("DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_currency_translation_currency' AND conrelid = 'currency_translation'::regclass) THEN ALTER TABLE currency_translation ADD CONSTRAINT FK_CURRENCY_TRANSLATION_CURRENCY FOREIGN KEY (currency_id) REFERENCES currency_currency (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE; END IF; END $$");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE currency_translation');
        $this->addSql('DROP TABLE currency_currency');
    }
}
