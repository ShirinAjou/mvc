<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\Game;
use App\Card\SessionGameMethods;
use App\Card\Turn;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    public function testPlayGame(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $sessionMethods = $this->createMock(SessionGameMethods::class);
        $turnSession = $this->createMock(Turn::class);
        $deck = new DeckOfCards();
        $game = new Game($session, $sessionMethods);
        $result = $game->playGame($deck, $session, $turnSession, false, false);

        $this->assertTrue($result);
    }

    public function testPlayGameWithDrawCard(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $sessionMethods = $this->createMock(SessionGameMethods::class);
        $turnSession = $this->createMock(Turn::class);

        $deck = new DeckOfCards();
        $game = new Game($session, $sessionMethods);
        $turnSession->expects($this->once())->method('playerTurn');

        $game->playGame($deck, $session, $turnSession, true, false);
    }

    public function testPlayGameWithStop(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $sessionMethods = $this->createMock(SessionGameMethods::class);
        $turnSession = $this->createMock(Turn::class);

        $deck = new DeckOfCards();
        $game = new Game($session, $sessionMethods);
        $turnSession->expects($this->once())->method('bankTurn');

        $game->playGame($deck, $session, $turnSession, false, true);
    }

    public function testWinner(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $sessionMethods = $this->createMock(SessionGameMethods::class);
        $game = new Game($session, $sessionMethods);

        $this->assertEquals("Bank wins", $game->winner(23, 17));
        $this->assertEquals("Player wins", $game->winner(15, 25));
        $this->assertEquals("Bank wins", $game->winner(18, 18));
        $this->assertEquals("Player wins", $game->winner(20, 18));
    }
}
