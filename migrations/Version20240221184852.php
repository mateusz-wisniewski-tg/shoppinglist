<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221184852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sharee (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, read_only BOOLEAN NOT NULL, user_id INT NOT NULL, shopping_list_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_648BC42DA76ED395 ON sharee (user_id)');
        $this->addSql('CREATE INDEX IDX_648BC42D23245BF9 ON sharee (shopping_list_id)');
        $this->addSql('CREATE TABLE shopping_list (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(500) NOT NULL, fulfilled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3DC1A459A76ED395 ON shopping_list (user_id)');
        $this->addSql('CREATE TABLE shopping_list_item (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, quantity_unit VARCHAR(10) NOT NULL, purchased BOOLEAN NOT NULL, shopping_list_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FB1C22423245BF9 ON shopping_list_item (shopping_list_id)');
        $this->addSql('CREATE TABLE "user" (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE sharee ADD CONSTRAINT FK_648BC42DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sharee ADD CONSTRAINT FK_648BC42D23245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A459A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_list_item ADD CONSTRAINT FK_4FB1C22423245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sharee DROP CONSTRAINT FK_648BC42DA76ED395');
        $this->addSql('ALTER TABLE sharee DROP CONSTRAINT FK_648BC42D23245BF9');
        $this->addSql('ALTER TABLE shopping_list DROP CONSTRAINT FK_3DC1A459A76ED395');
        $this->addSql('ALTER TABLE shopping_list_item DROP CONSTRAINT FK_4FB1C22423245BF9');
        $this->addSql('DROP TABLE sharee');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('DROP TABLE shopping_list_item');
        $this->addSql('DROP TABLE "user"');
    }
}
