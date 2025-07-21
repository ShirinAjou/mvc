<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Turn;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\CardGraphic;

class TurnTest extends TestCase
{
    public function testPlayerTurn()
    {
        $deck = new DeckOfCards();

        while ($deck->countCards() > 0) {
            $deck->drawReturn();
        }

        $card = $this->createMock(CardGraphic::class);
        $card->method('getValue')->willReturn(22);
        $card->method('getSuit')->willReturn(1);

        $deck->cards = [$card];

        $turn = new Turn();
        $result = $turn->playerTurn($deck);

        $this->assertEquals('lose', $result['status']);
    }

    public function testBankTurn()
    {
        $deck = new DeckOfCards();

        $card = $this->createMock(Card::class);
        $card->method('getValue')->willReturn(5);
    }
}
