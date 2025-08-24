<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Class representing a playing card with a value and suit.
 */
class Card
{
    private int $value;
    private int $suit;


    /**
     * Creates a card with a given value and suit.
     *
     * @param int $value The value of the card.
     * @param int $suit The suit of the card.
     */
    public function __construct(int $value, int $suit)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    /**
     * Returns the value of the card.
     *
     * @return int The value of the card.
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Returns the suit of the card.
     *
     * @return int The suit of the card.
     */
    public function getSuit(): int
    {
        return $this->suit;
    }

    /**
     * Returns the card as a string.
     *
     * @return string The card as a string.
     */
    public function __toString(): string
    {
        $graphic = new CardGraphic($this->value, $this->suit);
        return $graphic->getCard();
    }
}
