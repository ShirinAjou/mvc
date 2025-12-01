<?php

namespace App\Tests\Project;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Project\Player;
use App\Project\Score;

/**
 * Test cases for Player class.
 */
class PlayerTest extends TestCase
{
    public function testGetName(): void
    {
        $player = new Player("Hej");

        $this->assertEquals("Hej", $player->name);
    }

    public function testStandButton(): void
    {
        $player = new Player();
        $player->drawnCard = true;
        $result = $player->standButton();
        
        $this->assertEquals("stand", $player->status);
    }

    public function testPlayerDrawLose(): void
    {
        $score = new Score();
        $deck = new DeckOfCards();
        $player = new Player();

        $player->hand->hand[] = new CardGraphic(11, 1);
        $player->hand->hand[] = new CardGraphic(10, 2);
        $player->hand->hand[] = new CardGraphic(2, 3);
        $player->playerDraw($deck, $score);

        $this->assertEquals("lose", $player->status);
    }

    public function testPlayerDrawCards(): void
    {
        $score = new Score();
        $deck = new DeckOfCards();
        $player = new Player();
        $player->playerDraw($deck, $score);

        $this->assertEquals("", $player->message);
    }
}
