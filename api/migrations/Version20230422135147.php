<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230422135147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE device_tokens (id UUID NOT NULL, device_id UUID NOT NULL, expiration_time TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, token VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794A609594A4C7D4 ON device_tokens (device_id)');
        $this->addSql('COMMENT ON COLUMN device_tokens.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN device_tokens.device_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN device_tokens.expiration_time IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN device_tokens.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE device_tokens ADD CONSTRAINT FK_794A609594A4C7D4 FOREIGN KEY (device_id) REFERENCES devices (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD device_password VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE device_tokens DROP CONSTRAINT FK_794A609594A4C7D4');
        $this->addSql('DROP TABLE device_tokens');
        $this->addSql('ALTER TABLE users DROP device_password');
    }
}
