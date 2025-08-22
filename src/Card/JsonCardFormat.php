<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * 
 */
class JsonCardFormat
{
    private array $cards;

    public function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    /**
     * Converts the cards in the deck to JSON format.
     *
     * @return array An array of cards with their values and suits as strings.
     */
    public function getCardsForJson(): array
    {
        $suits = [
            1 => "spades",
            2 => "hearts",
            3 => "clubs",
            4 => "diamonds"
        ];

        $values = [
            1 => "A",
            2 => "2",
            3 => "3",
            4 => "4",
            5 => "5",
            6 => "6",
            7 => "7",
            8 => "8",
            9 => "9",
            10 => "10",
            11 => "J",
            12 => "Q",
            13 => "K"
        ];

        $getArray = [];

        foreach ($this->cards as $card) {
            $getArray[] = [
                'value' => $values[$card->getValue()],
                'suit' => $suits[$card->getSuit()],
            ];
        }

        return $getArray;
    }
}
