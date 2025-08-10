<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Represents a playing card with a rank and suit.
 */
class Card
{
    private int $rank;
    private int $suit;


    /**
     * Constructs a card with a given rank and suit.
     *
     * @param int $rank The rank of the card.
     * @param int $suit The suit of the card.
     */
    public function __construct(int $rank, int $suit)
    {
        $this->rank = $rank;
        $this->suit = $suit;
    }

    /**
     * Returns the rank of the card.
     *
     * @return int The rank of the card.
     */
    public function getValue(): int
    {
        return $this->rank;
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
     * Returns the card as a string using graphical representation.
     *
     * @return string The card as a string.
     */
    public function __toString(): string
    {
        $graphic = new CardGraphic($this->rank, $this->suit);
        return $graphic->getCard();
    }
}
