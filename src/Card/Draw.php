<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

/**
 * Represents the drawing actions in a card game.
 */
class Draw
{
    public function __construct()
    {
    }

    /**
     * Simulates a player drawing cards from the deck.
     *
     * @param array $playerHand representing player’s current hand.
     * @param DeckOfCards $deck representing deck of cards.
     *
     * @return array An array with the player’s updated hand, score, and deck.
     */
    public function playerDraw(array $playerHand, DeckOfCards $deck): array
    {
        $result = (new Turn())->playerTurn($deck);

        return [
            'hand' => array_merge($playerHand, $result['hand']),
            'score' => $result['score'],
            'deck' => $deck
        ];
    }

    /**
     * Simulates the bank drawing cards from the deck.
     *
     * @param array $bankHand representing bank’s current hand.
     * @param DeckOfCards $deck representing deck of cards.
     *
     * @return array An array with the bank’s updated hand, score, and deck.
     */
    public function bankDraw(array $bankHand, DeckOfCards $deck): array
    {
        $result = (new Turn())->bankTurn($deck);

        return [
            'hand' => array_merge($bankHand, $result['hand']),
            'score' => $result['score'],
            'deck' => $deck
        ];
    }
}
