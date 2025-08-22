<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Class representing a deck of cards.
 */
class DeckOfCards
{
    public array $cards;
    protected int $value;

    /**
     * Constructs a new deck of cards. The deck is initialized with 52 cards, each represented by a CardGraphic object.
     */
    public function __construct()
    {
        $this->cards = array();
        $suits = [1, 2, 3, 4];
        $values = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new CardGraphic($value, $suit);
            }
        }
    }

    /**
     * Returns the array of cards in the deck.
     *
     * @return array The array of cards.
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Sorts the cards in the deck by suit and value.
     *
     * @return array The sorted array of cards.
     */
    public function sortCards(): array
    {
        usort($this->cards, function ($card1, $card2) {
            $suitDifference = $card1->getSuit() - $card2->getSuit();
            if ($suitDifference !== 0) {
                return $suitDifference;
            }

            return $card1->getValue() - $card2->getValue();
        });

        return $this->cards;
    }

    /**
     * Shuffles the cards in the deck.
     *
     * @return array The shuffled array of cards.
     */
    public function shuffleCards(): array
    {
        shuffle($this->cards);

        return $this->cards;
    }

    /**
     * Counts the number of cards in the deck.
     *
     * @return int The number of cards in the deck.
     */
    public function countCards(): int
    {
        return count($this->cards);
    }

    /**
     * Draws a card from the deck and returns it.
     *
     * @return CardGraphic The drawn card.
     * @throws \RuntimeException If the deck is empty.
     */
    public function drawReturn(): CardGraphic
    {
        if (empty($this->cards)) {
            throw new \RuntimeException("Deck is empty.");
        }

        return array_shift($this->cards);
    }

    // /**
    //  * Converts the cards in the deck to JSON format.
    //  *
    //  * @return array An array of cards with their values and suits as strings.
    //  */
    // public function getCardsForJson(): array
    // {
    //     $suits = [
    //         1 => "spades",
    //         2 => "hearts",
    //         3 => "clubs",
    //         4 => "diamonds"
    //     ];

    //     $values = [
    //         1 => "A",
    //         2 => "2",
    //         3 => "3",
    //         4 => "4",
    //         5 => "5",
    //         6 => "6",
    //         7 => "7",
    //         8 => "8",
    //         9 => "9",
    //         10 => "10",
    //         11 => "J",
    //         12 => "Q",
    //         13 => "K"
    //     ];

    //     $getArray = [];

    //     foreach ($this->cards as $card) {
    //         $getArray[] = [
    //             'value' => $values[$card->getValue()],
    //             'suit' => $suits[$card->getSuit()],
    //         ];
    //     }

    //     return $getArray;
    // }

    /**
     * Adds a card to the deck.
     *
     * @param CardGraphic $card The card to add.
     */
    public function addCard(CardGraphic $card): void
    {
        $this->cards[] = $card;
    }
}
