<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Game;
use App\Card\SessionGameMethods;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for class SessionGameMethods.
 */
class SessionGameMethodsTest extends TestCase
{
    public function testSessionGame(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->method('get')->willReturnMap([
            ['bankHand', [], []],
            ['playerHand', [], []],
            ['playerScore', 0, 17],
            ['bankScore', 0, 20]
        ]);

        $game = new SessionGameMethods();
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

        $game = new SessionGameMethods();
        $result = $game->returnGame($sessionMock);

        $this->assertEquals(19, $result['player']['score']);
    }

    public function testGameData(): void
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMethodMock = $this->getMockBuilder(SessionGameMethods::class)
            ->onlyMethods(['sessionGame'])
            ->getMock();
        $gameMock = $this->getMockBuilder(Game::class)
            ->setConstructorArgs([$sessionMock, $sessionMethodMock])
            ->onlyMethods(['winner'])
            ->getMock();

        $sessionMethodMock->method('sessionGame')->willReturn([
            'playerScore' => 21,
            'bankScore' => 8,
            'playerHand' => ['PlayerCard'],
            'bankHand' => ['BankCard']
        ]);

        $gameMock->method('winner')->with(21, 8)->willReturn('Player wins');
        $result = $sessionMethodMock->gameData($sessionMock, $gameMock);

        $this->assertEquals(21, $result['playerScore']);
        $this->assertEquals(8, $result['bankScore']);
    }
}
