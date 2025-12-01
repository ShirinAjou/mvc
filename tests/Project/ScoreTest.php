<?php

namespace App\Tests\Project;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Project\Player;
use App\Project\Score;
use App\Project\Game;
use App\Project\Dealer;

/**
 * Test cases for Score class.
 */
class ScoreTest extends TestCase
{
    public function testGetScore(): void
    {
        $score = new Score();
        $player = new Player();
        $player->points = 7;
        $result = $score->getScore($player);

        $this->assertEquals(7, $result);
    }

    public function testUpdateScore(): void
    {
        $scoreManager = new Score();
        $player = new Player();
        $player->points = 7;

        $scoreManager->updateScore($player, 14);

        $this->assertEquals(14, $player->points);
    }
}
