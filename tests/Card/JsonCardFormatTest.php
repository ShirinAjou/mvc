<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\JsonCardFormat;

/**
 * Test cases for class JsonCardFormat.
 */
class JsonCardFormatTest extends TestCase
{
    public function testGetCardsForJson(): void
    {

        $card1 = new Card(1, 1);
        $card2 = new Card(2, 1);

        $cards = [$card1, $card2];

        $expectedArray = [
            ['value' => 'A', 'suit' => 'spades'],
            ['value' => '2', 'suit' => 'spades']
        ];

        $jsonFormatter = new JsonCardFormat($cards);
        $result = $jsonFormatter->getCardsForJson();

        $this->assertEquals($expectedArray, $result);
    }
}
