<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\Turn;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\CardGraphic;

/**
 * Test cases for class Turn.
 */
class TurnTest extends TestCase
{
    public function testPlayerTurn(): void
    {
        $deck = new DeckOfCards();
        $card = $this->createMock(CardGraphic::class);
        $card->method('getValue')->willReturn(22);
        $deck->cards = [];
        $deck->addCard($card);
        $turn = new Turn();
        $result = $turn->playerTurn($deck);

        $this->assertEquals('lose', $result['status']);
    }

    public function testBankTurn(): void
    {
        $deck = new DeckOfCards();
        $card = $this->createMock(CardGraphic::class);
        $card->method('getValue')->willReturn(5);
        $deck->addCard($card);
        $turn = new Turn();
        $result = $turn->bankTurn($deck);
        $this->assertGreaterThanOrEqual('17', $result['score']);
    }
}
