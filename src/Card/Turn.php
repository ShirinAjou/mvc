<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

/**
 * Class representing a turn in the card game.
 */
class Turn
{
    public function __construct()
    {
    }

    /**
     * Simulates a player's turn in the card game.
     * If the player's score exceeds 21, they lose the game.
     *
     * @param DeckOfCards $deck The deck of cards to draw from.
     * @return array An array containing the player's hand, score, and status.
     */
    public function playerTurn(DeckOfCards $deck): array
    {
        $status = 'playing';
        $playerHand = [];
        $playerScore = 0;

        if ($deck->countCards() > 0) {
            $cardHand = new CardHand();
            $card = $cardHand->drawCard($deck);
            $playerHand[] = $card;
            $playerScore += $card->getValue();
        }

        if ($playerScore > 21) {
            return [
                'hand' => $playerHand,
                'score' => $playerScore,
                'status' => 'lose'
            ];
        }

        return [
            'hand' => $playerHand,
            'score' => $playerScore,
            'status' => $status
        ];
    }

    /**
     * Simulates the bank's turn in the card game. Draws card until reaching 17 points.
     *
     * @param DeckOfCards $deck The deck of cards to draw from.
     * @return array An array containing the bank's hand and score.
     */
    public function bankTurn(DeckOfCards $deck): array
    {
        $bankHand = [];
        $bankScore = 0;

        while ($bankScore < 17 && $deck->countCards() > 0) {
            $cardHand = new CardHand();
            $card = $cardHand->drawCard($deck);
            $bankHand[] = $card;
            $bankScore += $card->getValue();
        }

        return [
            'hand' => $bankHand,
            'score' => $bankScore
        ];
    }
}
