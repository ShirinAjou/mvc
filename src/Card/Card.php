<?php

namespace App\Card;

use App\Card\CardGraphic;

class Card
{
    private int $rank;
    private int $suit;

    public function __construct(int $rank, int $suit)
    {
        $this->rank = $rank;
        $this->suit = $suit;
    }

    public function getValue(): int
    {
        return $this->rank;
    }

    public function getSuit(): int
    {
        return $this->suit;
    }


    public function __toString(): string
    {
        $graphic = new CardGraphic($this->rank, $this->suit);
        return $graphic->getCard();
    }

}
