<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251201074549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dance (id SERIAL NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, bpm INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_184BFD6F7E3C61F9 ON dance (owner_id)');
        $this->addSql('CREATE TABLE dance_school (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE dance_school_allowed_user (dance_school_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(dance_school_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A73D8F72E92591D1 ON dance_school_allowed_user (dance_school_id)');
        $this->addSql('CREATE INDEX IDX_A73D8F72A76ED395 ON dance_school_allowed_user (user_id)');
        $this->addSql('CREATE TABLE dance_school_allowed_admin_user (dance_school_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(dance_school_id, user_id))');
        $this->addSql('CREATE INDEX IDX_6E5AA37DE92591D1 ON dance_school_allowed_admin_user (dance_school_id)');
        $this->addSql('CREATE INDEX IDX_6E5AA37DA76ED395 ON dance_school_allowed_admin_user (user_id)');
        $this->addSql('CREATE TABLE steps (id SERIAL NOT NULL, dance_id_id INT DEFAULT NULL, m1_x DOUBLE PRECISION NOT NULL, m1_y DOUBLE PRECISION NOT NULL, m1_toe BOOLEAN NOT NULL, m1_heel BOOLEAN NOT NULL, m1_rotate INT NOT NULL, m2_x DOUBLE PRECISION NOT NULL, m2_y DOUBLE PRECISION NOT NULL, m2_toe BOOLEAN NOT NULL, m2_heel BOOLEAN NOT NULL, m2_rotate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_34220A72E25A765C ON steps (dance_id_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE dance ADD CONSTRAINT FK_184BFD6F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES dance_school (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dance_school_allowed_user ADD CONSTRAINT FK_A73D8F72E92591D1 FOREIGN KEY (dance_school_id) REFERENCES dance_school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dance_school_allowed_user ADD CONSTRAINT FK_A73D8F72A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dance_school_allowed_admin_user ADD CONSTRAINT FK_6E5AA37DE92591D1 FOREIGN KEY (dance_school_id) REFERENCES dance_school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dance_school_allowed_admin_user ADD CONSTRAINT FK_6E5AA37DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE steps ADD CONSTRAINT FK_34220A72E25A765C FOREIGN KEY (dance_id_id) REFERENCES dance (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dance DROP CONSTRAINT FK_184BFD6F7E3C61F9');
        $this->addSql('ALTER TABLE dance_school_allowed_user DROP CONSTRAINT FK_A73D8F72E92591D1');
        $this->addSql('ALTER TABLE dance_school_allowed_user DROP CONSTRAINT FK_A73D8F72A76ED395');
        $this->addSql('ALTER TABLE dance_school_allowed_admin_user DROP CONSTRAINT FK_6E5AA37DE92591D1');
        $this->addSql('ALTER TABLE dance_school_allowed_admin_user DROP CONSTRAINT FK_6E5AA37DA76ED395');
        $this->addSql('ALTER TABLE steps DROP CONSTRAINT FK_34220A72E25A765C');
        $this->addSql('DROP TABLE dance');
        $this->addSql('DROP TABLE dance_school');
        $this->addSql('DROP TABLE dance_school_allowed_user');
        $this->addSql('DROP TABLE dance_school_allowed_admin_user');
        $this->addSql('DROP TABLE steps');
        $this->addSql('DROP TABLE "user"');
    }
}
