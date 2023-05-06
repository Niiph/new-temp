<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230506141425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sensor_settings (id UUID NOT NULL, sensor_id UUID NOT NULL, minimum INT DEFAULT NULL, maximum INT DEFAULT NULL, type VARCHAR(8) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_735111CCA247991F ON sensor_settings (sensor_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_735111CCA247991F8CDE5729 ON sensor_settings (sensor_id, type)');
        $this->addSql('COMMENT ON COLUMN sensor_settings.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN sensor_settings.sensor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN sensor_settings.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('ALTER TABLE sensor_settings ADD CONSTRAINT FK_735111CCA247991F FOREIGN KEY (sensor_id) REFERENCES sensors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE readings ADD type VARCHAR(8) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sensor_settings DROP CONSTRAINT FK_735111CCA247991F');
        $this->addSql('DROP TABLE sensor_settings');
        $this->addSql('ALTER TABLE readings DROP type');
    }
}
