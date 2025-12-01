<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251127192223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, result VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE players (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(250) DEFAULT NULL, type BOOLEAN NOT NULL, score INTEGER DEFAULT NULL, hand VARCHAR(255) DEFAULT NULL, status VARCHAR(250) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE "BINARY", value INTEGER NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE game
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE players
        SQL);
    }
}
