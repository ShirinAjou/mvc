<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128154330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE playe_blackjack ADD COLUMN status VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__playe_blackjack AS SELECT id, gamepl_id, name, score, dealer, cardhand FROM playe_blackjack
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE playe_blackjack
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE playe_blackjack (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gamepl_id INTEGER NOT NULL, name VARCHAR(255) DEFAULT NULL, score INTEGER DEFAULT NULL, dealer BOOLEAN NOT NULL, cardhand CLOB DEFAULT NULL --(DC2Type:json)
            , CONSTRAINT FK_4511CEA561305F7C FOREIGN KEY (gamepl_id) REFERENCES game_black_jack (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO playe_blackjack (id, gamepl_id, name, score, dealer, cardhand) SELECT id, gamepl_id, name, score, dealer, cardhand FROM __temp__playe_blackjack
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__playe_blackjack
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4511CEA561305F7C ON playe_blackjack (gamepl_id)
        SQL);
    }
}
