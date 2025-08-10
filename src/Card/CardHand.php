<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;

/**
 * Represents a hand of cards in a card game.
 */
class CardHand
{
    public array $hand;

    public function __construct()
    {
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
}
