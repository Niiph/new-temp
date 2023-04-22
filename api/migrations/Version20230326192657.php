<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326192657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devices (id UUID NOT NULL, user_id UUID DEFAULT NULL, short_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_11074E9AA76ED395 ON devices (user_id)');
        $this->addSql('COMMENT ON COLUMN devices.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN devices.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN devices.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE devices ADD CONSTRAINT FK_11074E9AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sensors DROP CONSTRAINT fk_d0d3fa90a76ed395');
        $this->addSql('DROP INDEX idx_d0d3fa90a76ed395');
        $this->addSql('ALTER TABLE sensors DROP short_id');
        $this->addSql('ALTER TABLE sensors RENAME COLUMN user_id TO device_id');
        $this->addSql('ALTER TABLE sensors ADD CONSTRAINT FK_D0D3FA9094A4C7D4 FOREIGN KEY (device_id) REFERENCES devices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D0D3FA9094A4C7D4 ON sensors (device_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sensors DROP CONSTRAINT FK_D0D3FA9094A4C7D4');
        $this->addSql('ALTER TABLE devices DROP CONSTRAINT FK_11074E9AA76ED395');
        $this->addSql('DROP TABLE devices');
        $this->addSql('DROP INDEX IDX_D0D3FA9094A4C7D4');
        $this->addSql('ALTER TABLE sensors ADD short_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sensors RENAME COLUMN device_id TO user_id');
        $this->addSql('ALTER TABLE sensors ADD CONSTRAINT fk_d0d3fa90a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d0d3fa90a76ed395 ON sensors (user_id)');
    }
}
