<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251127205906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE game_black_jack (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE playe_blackjack (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gamepl_id INTEGER NOT NULL, name VARCHAR(255) DEFAULT NULL, score INTEGER DEFAULT NULL, dealer BOOLEAN NOT NULL, CONSTRAINT FK_4511CEA561305F7C FOREIGN KEY (gamepl_id) REFERENCES game_black_jack (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4511CEA561305F7C ON playe_blackjack (gamepl_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE blackjack_game
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE blackjack_player
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE blackjack_game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, result VARCHAR(255) DEFAULT NULL COLLATE "BINARY")
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE blackjack_player (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL COLLATE "BINARY", type BOOLEAN NOT NULL, score INTEGER DEFAULT NULL, hand VARCHAR(255) DEFAULT NULL COLLATE "BINARY", status VARCHAR(255) DEFAULT NULL COLLATE "BINARY")
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE game_black_jack
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE playe_blackjack
        SQL);
    }
}
