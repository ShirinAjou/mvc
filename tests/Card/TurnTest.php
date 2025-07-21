<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Turn;
use App\Card\DeckOfCards;
use App\Card\Card;
use App\Card\CardHand;

/**
 * Test cases for class Turn.
 */
class TurnTest extends TestCase
{
    /**
     * 
     */
    public function testplayerTurn()
    {
        $score = 0;
        $list = [];

        $hand = new CardHand();
        $deck = new DeckOfCards();
        $turn = new Turn();
        $card = $hand->drawCard($deck);
        $card = $playerHand->drawCard($deck);
        $result = $turn->playerTurn($deck);

        while ($score <= 21) {
            $card = $cardHand->drawCard($deck);
            $list[] = $card;
            $score += $card->getValue();
        }
        $this->assertEquals('lose', $result['status']);
    }
}
