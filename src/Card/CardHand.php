<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;

/**
 * Class representing a hand of cards in a card game.
 */
class CardHand
{
    public array $hand;

    public function __construct()
    {
        $this->hand = [];
    }

    /**
     * Draws a card from the deck and adds it to the hand.
     *
     * @param DeckOfCards $deck The deck to draw a card from.
     * @return CardGraphic The card that was drawn.
     */
    public function drawCard(DeckOfCards $deck): CardGraphic
    {
        $card = $deck->drawReturn();
        $this->hand[] = $card;
        return $card;
    }

    /**
     * Shows card in JSON format.
     *
     * @return array Array with card.
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

        $result = [];

        foreach ($this->hand as $card) {
            $result[] = [
                "value" => $values[$card->getValue()],
                "suit" => $suits[$card->getSuit()],
                "symbol" => $card->getCard()
            ];
        }

        return $result;
    }
}
