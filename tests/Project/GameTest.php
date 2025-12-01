<?php

namespace App\Tests\Project;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Project\Player;
use App\Project\Score;
use App\Project\Game;
use App\Project\Dealer;

/**
 * Test cases for Game class.
 */
class GameTest extends TestCase
{
    public function testNumberPlayers(): void
    {
        $player = new Player();
        $game = new Game();
        $result = $game->numberPlayers(4);

        $this->assertCount(4, $result);
        $this->assertEquals("Player 4", $result[3]->getName());
    }

    public function testPlayerTurn(): void
    {
        $score = new Score();
        $game = new Game();

        $player = new Player();
        $player->status = "playing";
        $player->hand->hand[] = new CardGraphic(5, 1);  
        $players = [$player];
        $result = $game->playerTurn($players, $score, 0);

        $this->assertEquals(0, $result);
    }

    public function testPlayerTurnIndex(): void
    {
        $score = new Score();
        $game = new Game();

        $player = new Player();
        $player->status = "playing";
        $player->hand->hand[] = new CardGraphic(10, 1);
        $player->hand->hand[] = new CardGraphic(10, 2);
        $player->hand->hand[] = new CardGraphic(10, 3); 
        $players = [$player];
        $result = $game->playerTurn($players, $score, 0);

        $this->assertEquals(0, $result);
    }

    public function testGetWinner(): void
    {
        $score = new Score();
        $game = new Game();

        $player = new Player();
        $player->status = "playing";
        $player->hand->hand[] = new CardGraphic(5, 1); 
        $players = [$player];

        $dealer = new Dealer();
        $dealer->dealerHand->hand[] = new CardGraphic(10, 3);
        $dealer->dealerHand->hand[] = new CardGraphic(1, 3); 
        $dealer->dealerHand->hand[] = new CardGraphic(10, 3); 
        $dealer->dealerPoints = $score->manageScore($dealer->dealerHand);
        $result = $game->getWinner($players, $dealer);

        $this->assertEquals(["Dealer"], $result);
    }

    public function testGetWinnerNoWinner(): void
    {
        $score = new Score();
        $game = new Game();

        $player = new Player();
        $player->status = "playing";
        $player->hand->hand[] = new CardGraphic(10, 1);
        $player->hand->hand[] = new CardGraphic(10, 2);
        $player->hand->hand[] = new CardGraphic(10, 3);
        $players = [$player];

        $dealer = new Dealer();
        $dealer->dealerHand->hand[] = new CardGraphic(8, 1);
        $dealer->dealerHand->hand[] = new CardGraphic(8, 2);
        $dealer->dealerHand->hand[] = new CardGraphic(8, 3);
        $dealer->dealerPoints = $score->manageScore($dealer->dealerHand);
        $result = $game->getWinner($players, $dealer);

        $this->assertEquals(["Ingen vinnare"], $result);
    }

    public function testGetWinnerDraw(): void
    {
        $score = new Score();
        $game = new Game();

        $player = new Player();
        $player->name = "player 1";
        $player->status = "playing";
        $player->hand->hand[] = new CardGraphic(6, 1);
        $player->hand->hand[] = new CardGraphic(6, 2);
        $player->hand->hand[] = new CardGraphic(6, 3);
        $players = [$player];

        $dealer = new Dealer();
        $dealer->dealerHand->hand[] = new CardGraphic(6, 1);
        $dealer->dealerHand->hand[] = new CardGraphic(6, 2);
        $dealer->dealerHand->hand[] = new CardGraphic(6, 3);
        $dealer->dealerPoints = $score->manageScore($dealer->dealerHand);

        $result = $game->getWinner($players, $dealer);

        $this->assertContains("player 1", $result);
        $this->assertContains("Dealer", $result);
    }

    public function testEndGame(): void
    {
        $game = new Game();
        $player1 = new Player();
        $player1->status = "stand";

        $player2 = new Player();
        $player2->status = "playing";
        $players1 = [$player1];
        $players2 = [$player2];

        $result1 = $game->endGame($players1);
        $result2 = $game->endGame($players2);

        $this->assertEquals(true, $result1);
        $this->assertEquals(false, $result2);
    }

    public function testTwoCards(): void
    {
        $deck = new DeckOfCards();
        $score = new Score();
        $game = new Game();

        $players = $game->numberPlayers(1);
        $game->twoCards($deck, $score);
        $player = $players[0];

        $this->assertCount(2, $player->hand->hand);
    }
}
