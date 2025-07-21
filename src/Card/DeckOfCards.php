<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    /** @var CardGraphic[] */
    public array $cards;
    protected int $value;

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

    /** @return CardGraphic[] */
    public function getCards(): array
    {
        return $this->cards;
    }

    /** @return CardGraphic[] */
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

    /** @return CardGraphic[] */
    public function shuffleCards(): array
    {
        shuffle($this->cards);

        return $this->cards;
    }

    public function countCards(): int
    {
        return count($this->cards);
    }

    public function drawReturn(): CardGraphic
    {
        if (empty($this->cards)) {
            throw new \RuntimeException("Deck is empty.");
        }

        return array_shift($this->cards);
    }

    /** @return array<array<string, string>> */
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
