<?php

namespace App\Card;

/**
 * Represents a card with a graphical representation.
 */
class CardGraphic extends Card
{
    /**
     * Returns the card as a string with value and suit symbols.
     *
     * @return string The card as a string.
     */
    public function getCard(): string
    {
        $suitSymbols = [1 => "\u{2660}", 2 => "\u{2665}", 3 => "\u{2663}", 4 => "\u{2666}"];
        $valueSymbols = [1 => "A", 2 => "2", 3 => "3", 4 => "4", 5 => "5", 6 => "6", 7 => "7", 8 => "8", 9 => "9", 10 => "10", 11 => "J", 12 => "Q", 13 => "K"];

        $value = parent::getValue();
        $suit = parent::getSuit();

        return $valueSymbols[$value] . $suitSymbols[$suit];
    }

    /**
     * Returns the card as a string.
     *
     * @return string The card as a string.
     */
    public function __toString(): string
    {
        return $this->getCard();
    }

    /**
     * Returns the suit of the card as a word.
     *
     * @return string The suit of the card as a word.
     */
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
