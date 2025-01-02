<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231229143415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX email_idx ON "user" (email)');
        $this->addSql('CREATE INDEX first_name_idx ON "user" (first_name)');
        $this->addSql('CREATE INDEX last_name_idx ON "user" (last_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX email_idx');
        $this->addSql('DROP INDEX first_name_idx');
        $this->addSql('DROP INDEX last_name_idx');
    }
}
