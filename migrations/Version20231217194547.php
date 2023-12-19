<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217194547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE reaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reaction (id INT NOT NULL, author_id INT NOT NULL, type VARCHAR(255) NOT NULL, target_id INT NOT NULL, target_type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A4D707F7F675F31B ON reaction (author_id)');
        $this->addSql('COMMENT ON COLUMN reaction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE reaction_id_seq CASCADE');
        $this->addSql('ALTER TABLE reaction DROP CONSTRAINT FK_A4D707F7F675F31B');
        $this->addSql('DROP TABLE reaction');
    }
}
