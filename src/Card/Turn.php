<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\Turn;

class Turn
{
    public function __construct()
    {
    }

    /**
     * @return array{
     *     hand: array<Card>,
     *     score: int,
     *     status?: string
     * }
     */
    public function playerTurn(DeckOfCards $deck): array
    {
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
            'score' => $playerScore
        ];
    }

    /**
     * @return array{
     *     hand: array<Card>,
     *     score: int
     * }
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
