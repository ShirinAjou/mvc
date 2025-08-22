<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;
use App\Card\JsonGameFormat;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Test cases for class JsonGameFormat.
 */
class JsonGameFormatTest extends TestCase
{
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

        $game = new JsonGameFormat();
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

        $game = new JsonGameFormat();
        $result = $game->jsonGame($sessionMock);

        $expectedBankHand = [['value' => 'J', 'suit' => 'Diamonds']];
        $this->assertEquals($expectedBankHand, $result['bank']['hand']);
    }
}
