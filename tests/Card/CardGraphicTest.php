<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * 
     */
    public function testGetValue()
    {
        $graphic = new CardGraphic(10, 4);
        $this->assertEquals("10" . "\u{2666}", $graphic->getCard());
    }

    /**
     * 
     */
    public function testgetSuitAsWord()
    {
        $graphic = new CardGraphic(7, 2);
        $this->assertEquals("hearts", $graphic->getSuitAsWord());
    }

    /**
     * 
     */
    public function testToString()
    {
        $card = new CardGraphic(1, 2);
        $this->assertIsString((string)$card);
    }
}