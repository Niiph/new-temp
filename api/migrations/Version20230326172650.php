<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326172650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE readings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE readings (id INT NOT NULL, sensor_id UUID NOT NULL, value DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1A14A4F1A247991F ON readings (sensor_id)');
        $this->addSql('COMMENT ON COLUMN readings.sensor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN readings.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE sensors (id UUID NOT NULL, user_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, pin INT NOT NULL, address VARCHAR(255) NOT NULL, minimum INT DEFAULT NULL, maximum INT DEFAULT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D0D3FA90A76ED395 ON sensors (user_id)');
        $this->addSql('COMMENT ON COLUMN sensors.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN sensors.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN sensors.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles TEXT NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE readings ADD CONSTRAINT FK_1A14A4F1A247991F FOREIGN KEY (sensor_id) REFERENCES sensors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sensors ADD CONSTRAINT FK_D0D3FA90A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE sensor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE readings_id_seq CASCADE');
        $this->addSql('CREATE TABLE sensor (id UUID NOT NULL, name VARCHAR(255) NOT NULL, pin INT NOT NULL, address VARCHAR(255) NOT NULL, minimum INT DEFAULT NULL, maximum INT DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN sensor.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE readings DROP CONSTRAINT FK_1A14A4F1A247991F');
        $this->addSql('ALTER TABLE sensors DROP CONSTRAINT FK_D0D3FA90A76ED395');
        $this->addSql('DROP TABLE readings');
        $this->addSql('DROP TABLE sensors');
        $this->addSql('DROP TABLE users');
    }
}
