<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardHand;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * 
     */
    public function testdrawCard()
    {
        $deck = new DeckOfCards();
        $hand = new CardHand();
        $card = $hand->drawCard($deck);
        $this->assertInstanceOf(CardGraphic::class, $card);
    }
}