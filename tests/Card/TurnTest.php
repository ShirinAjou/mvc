<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Turn;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\CardGraphic; // ✅ glömd import från förra försöket

class TurnTest extends TestCase
{
    // public function testPlayerTurnReturnsExpectedScore()
    // {
    //     $deck = new DeckOfCards();

    //     // Töm originalkortleken
    //     while ($deck->countCards() > 0) {
    //         $deck->drawReturn();
    //     }

    //     // Lägg till ett enda testkort: 10 i hjärter (suit 2)
    //     $testCard = new CardGraphic(10, 2);
    //     $deck->cards = [$testCard]; // OK eftersom cards är public

    //     $turn = new Turn();
    //     $result = $turn->playerTurn($deck);

    //     $this->assertEquals(10, $result['score']);
    //     $this->assertCount(1, $result['hand']);
    //     $this->assertArrayNotHasKey('status', $result); // ska inte ha "lose"
    // }

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
