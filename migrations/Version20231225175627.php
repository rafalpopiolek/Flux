<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231225175627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, post_id INT NOT NULL, author_id INT NOT NULL, content VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, post_id INT NOT NULL, filename VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10C4B89032C ON media (post_id)');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, author_id INT NOT NULL, content VARCHAR(500) NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('COMMENT ON COLUMN post.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, user_id INT NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, twitter_url VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, bio VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0FA76ED395 ON profile (user_id)');
        $this->addSql('CREATE TABLE reaction (id INT NOT NULL, author_id INT NOT NULL, type VARCHAR(255) NOT NULL, target_id INT NOT NULL, target_type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A4D707F7F675F31B ON reaction (author_id)');
        $this->addSql('COMMENT ON COLUMN reaction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE followers (user_source INT NOT NULL, user_target INT NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX IDX_8408FDA73AD8644E ON followers (user_source)');
        $this->addSql('CREATE INDEX IDX_8408FDA7233D34C1 ON followers (user_target)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA73AD8644E FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7233D34C1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reaction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10C4B89032C');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0FA76ED395');
        $this->addSql('ALTER TABLE reaction DROP CONSTRAINT FK_A4D707F7F675F31B');
        $this->addSql('ALTER TABLE followers DROP CONSTRAINT FK_8408FDA73AD8644E');
        $this->addSql('ALTER TABLE followers DROP CONSTRAINT FK_8408FDA7233D34C1');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE reaction');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE followers');
    }
}
