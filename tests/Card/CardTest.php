<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    public function testGetValue(): void
    {
        $card = new Card(2, 3);
        $this->assertEquals(2, $card->getValue());
    }

    public function testGetSuit(): void
    {
        $card = new Card(3, 4);
        $this->assertEquals(4, $card->getSuit());
    }

    public function testToString(): void
    {
        $card = new Card(1, 2);
        $cardString = "A" . "\u{2665}";
        $this->assertEquals($cardString, (string)$card);
    }
}
