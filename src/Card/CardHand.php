<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;

class CardHand
{
    public array $hand;

    public function __construct()
    {
    }

    public function drawCard(DeckOfCards $deck): CardGraphic
    {
        $card = $deck->drawReturn();
        $this->hand[] = $card;
        return $card;
    }
}
