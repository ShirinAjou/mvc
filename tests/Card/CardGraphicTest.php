<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    public function testGetValue(): void
    {
        $graphic = new CardGraphic(10, 4);
        $this->assertEquals("10" . "\u{2666}", $graphic->getCard());
    }

    public function testGetSuitAsWord(): void
    {
        $graphic = new CardGraphic(7, 2);
        $this->assertEquals("hearts", $graphic->getSuitAsWord());
    }

    public function testToString(): void
    {
        $graphic = new CardGraphic(1, 2);
        $cardString = "A" . "\u{2665}";
        $this->assertEquals($cardString, (string)$graphic);
    }
}
