<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\DeckOfCards;

class CardHand
{
    public function __construct()
    {
    }

    public function drawCard(DeckOfCards $deck): CardGraphic
    {
        return $deck->drawReturn();
    }
}
