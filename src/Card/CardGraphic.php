<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function getCard(): string
    {
        $suitSymbols = [1 => "\u{2660}", 2 => "\u{2665}", 3 => "\u{2663}", 4 => "\u{2666}"];
        $rankSymbols = [1 => "A", 2 => "2", 3 => "3", 4 => "4", 5 => "5", 6 => "6", 7 => "7", 8 => "8", 9 => "9", 10 => "10", 11 => "J", 12 => "Q", 13 => "K"];

        $rank = parent::getValue();
        $suit = parent::getSuit();

        return $rankSymbols[$rank] . $suitSymbols[$suit];
    }

    public function __toString(): string
    {
        return $this->getCard();
    }

    public function getSuitAsWord(): string
    {
        $suits = [
            1 => "spades",
            2 => "hearts",
            3 => "clubs",
            4 => "diamonds"
        ];

        return $suits[$this->getSuit()];
    }

}
