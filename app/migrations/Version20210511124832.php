<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511124832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__highscore AS SELECT id, name, score, date FROM highscore');
        $this->addSql('DROP TABLE highscore');
        $this->addSql('CREATE TABLE highscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY, score VARCHAR(255) DEFAULT NULL COLLATE BINARY, date DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO highscore (id, name, score, date) SELECT id, name, score, date FROM __temp__highscore');
        $this->addSql('DROP TABLE __temp__highscore');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__highscore AS SELECT id, name, score, date FROM highscore');
        $this->addSql('DROP TABLE highscore');
        $this->addSql('CREATE TABLE highscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, score VARCHAR(255) DEFAULT NULL, date VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO highscore (id, name, score, date) SELECT id, name, score, date FROM __temp__highscore');
        $this->addSql('DROP TABLE __temp__highscore');
    }
}
