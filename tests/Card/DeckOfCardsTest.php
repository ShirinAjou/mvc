<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardHand;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    public function testGetCards(): void
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->getCards());
    }

    public function testSortCards(): void
    {
        $deckToSort = new DeckOfCards();
        $expectedDeck = new DeckOfCards();
        $this->assertEquals($expectedDeck->getCards(), $deckToSort->sortCards());
    }

    public function testShuffleCards(): void
    {
        $deckToShuffle = new DeckOfCards();
        $shuffledDeck = new DeckOfCards();
        $this->assertNotEquals($deckToShuffle->shuffleCards(), $shuffledDeck->shuffleCards());
    }

    public function testCountCards(): void
    {
        $deck = new DeckOfCards();
        $this->assertEquals(52, $deck->countCards());
    }

    public function testDrawReturn(): void
    {
        $deck = new DeckOfCards();
        while (!empty($deck->getCards())) {
            $deck->drawReturn();
        }
        $this->expectException(\RuntimeException::class);
        $deck->drawReturn();
    }

    // public function testGetCardsForJson(): void
    // {
    //     $deck = new DeckOfCards();
    //     $expectedArray = [
    //         ['value' => 'A', 'suit' => 'spades'],
    //         ['value' => '2', 'suit' => 'spades'],
    //     ];

    //     $deck->getCardsForJson();
    //     $this->assertEquals($expectedArray, array_slice($deck->getCardsForJson(), 0, 2));
    // }
}
