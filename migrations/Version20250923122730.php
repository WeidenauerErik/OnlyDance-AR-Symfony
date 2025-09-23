<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923122730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dance (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, bpm INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE steps (id SERIAL NOT NULL, dance_id_id INT DEFAULT NULL, m1_x DOUBLE PRECISION NOT NULL, m1_y DOUBLE PRECISION NOT NULL, m1_toe BOOLEAN NOT NULL, m1_heel BOOLEAN NOT NULL, m1_rotate INT NOT NULL, m2_x DOUBLE PRECISION NOT NULL, m2_y DOUBLE PRECISION NOT NULL, m2_toe BOOLEAN NOT NULL, m2_heel BOOLEAN NOT NULL, m2_rotate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_34220A72E25A765C ON steps (dance_id_id)');
        $this->addSql('ALTER TABLE steps ADD CONSTRAINT FK_34220A72E25A765C FOREIGN KEY (dance_id_id) REFERENCES dance (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE steps DROP CONSTRAINT FK_34220A72E25A765C');
        $this->addSql('DROP TABLE dance');
        $this->addSql('DROP TABLE steps');
    }
}
