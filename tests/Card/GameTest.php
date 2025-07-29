<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\Game;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\CardGraphic;
use App\Card\Turn;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    public function testPlayGame(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $turnSession = $this->createMock(Turn::class);
        $deck = new DeckOfCards();
        $game = new Game();
        $result = $game->playGame($deck, $session, $turnSession, false, false);

        $this->assertTrue($result);
    }
    // public function testPlayGame(): void
    // {
    //     $session = $this->createMock(SessionInterface::class);
    //     $turnSession = $this->createMock(Turn::class);
    //     $deck = new DeckOfCards();
    //     $game = new Game();
    //     $result = $game->playGame($deck, $session, $turnSession);

    //     $this->assertTrue($result);
    // }

    // public function testPlayGameWithDrawCard(): void
    // {
    //     $_POST['drawCard'] = true;
    //     $mockSession = $this->createMock(SessionInterface::class);
    //     $turnSession = $this->createMock(Turn::class);

    //     $deck = new DeckOfCards();
    //     $game = new Game();
    //     $turnSession->expects($this->once())->method('playerTurn');

    //     $game->playGame($deck, $mockSession, $turnSession);
    // }

    // public function testPlayGameWithStop(): void
    // {
    //     $_POST['stop'] = true;
    //     $mockSession = $this->createMock(SessionInterface::class);
    //     $turnSession = $this->createMock(Turn::class);

    //     $deck = new DeckOfCards();
    //     $game = new Game();
    //     $turnSession->expects($this->once())->method('bankTurn');

    //     $game->playGame($deck, $mockSession, $turnSession);
    // }

    public function testPlayGameWithDrawCard(): void
    {
        $mockSession = $this->createMock(SessionInterface::class);
        $turnSession = $this->createMock(Turn::class);

        $deck = new DeckOfCards();
        $game = new Game();
        $turnSession->expects($this->once())->method('playerTurn');

        $game->playGame($deck, $mockSession, $turnSession, true, false);
    }

    public function testPlayGameWithStop(): void
    {
        $mockSession = $this->createMock(SessionInterface::class);
        $turnSession = $this->createMock(Turn::class);

        $deck = new DeckOfCards();
        $game = new Game();
        $turnSession->expects($this->once())->method('bankTurn');

        $game->playGame($deck, $mockSession, $turnSession, false, true);
    }

    public function testWinner(): void
    {
        $game = new Game();

        $this->assertEquals("Bank wins", $game->winner(23, 17));
        $this->assertEquals("Player wins", $game->winner(15, 25));
        $this->assertEquals("Bank wins", $game->winner(18, 18));
        $this->assertEquals("Player wins", $game->winner(20, 18));
    }

    public function testSessionGame(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturnMap([
            ['bankHand', [], []],
            ['playerHand', [], []],
            ['playerScore', 0, 17],
            ['bankScore', 0, 20]
        ]);

        $game = new Game();
        $result = $game->sessionGame($sessionMock);

        $this->assertEquals(17, $result['playerScore']);
        $this->assertEquals(20, $result['bankScore']);
    }

    public function testReturnGame(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);

        $sessionMock->method('get')->willReturnMap([
            ['bankHand', [], []],
            ['playerHand', [], []],
            ['playerScore', 0, 19],
            ['bankScore', 0, 17]
        ]);

        $game = new Game();
        $result = $game->returnGame($sessionMock);

        $this->assertEquals(19, $result['player']['score']);
    }

    public function testJsonGame(): void
    {
        $cardMock = $this->createMock(CardGraphic::class);
        $cardMock->method('getValue')->willReturn(13);
        $cardMock->method('getSuitAsWord')->willReturn('Spades');

        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturnMap([
            ['playerHand', [], [$cardMock]],
            ['bankHand', [], []],
            ['playerScore', 0, 10],
            ['bankScore', 0, 12],
        ]);

        $game = new Game();
        $result = $game->jsonGame($sessionMock);

        $expectedPlayerHand = [['value' => 'K', 'suit' => 'Spades']];
        $this->assertEquals($expectedPlayerHand, $result['player']['hand']);
    }

    public function testJsonBankHand(): void
    {
        $bankCard = $this->createMock(CardGraphic::class);
        $bankCard->method('getValue')->willReturn(11);
        $bankCard->method('getSuitAsWord')->willReturn('Diamonds');

        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturnMap([
            ['playerHand', [], []],
            ['bankHand', [], [$bankCard]],
            ['playerScore', 0, 10],
            ['bankScore', 0, 17],
        ]);

        $game = new Game();
        $result = $game->jsonGame($sessionMock);

        $expectedBankHand = [['value' => 'J', 'suit' => 'Diamonds']];
        $this->assertEquals($expectedBankHand, $result['bank']['hand']);
    }

    public function testGameData(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $gameMock = $this->getMockBuilder(Game::class)
            ->onlyMethods(['sessionGame', 'winner'])
            ->getMock();

        $gameMock->method('sessionGame')->willReturn([
            'playerScore' => 21,
            'bankScore' => 8,
            'playerHand' => ['PlayerCard'],
            'bankHand' => ['BankCard']
        ]);

        $gameMock->method('winner')->with(21, 8)->willReturn('Player wins');
        $result = $gameMock->gameData($sessionMock);

        $this->assertEquals(21, $result['playerScore']);
        $this->assertEquals(8, $result['bankScore']);
    }
}
