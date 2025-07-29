<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

class Draw
{
    public function __construct()
    {
    }

    public function playerDraw(array $playerHand, DeckOfCards $deck): array
    {
        $result = (new Turn())->playerTurn($deck);

        return [
            'hand' => array_merge($playerHand, $result['hand']),
            'score' => $result['score'],
            'deck' => $deck
        ];
    }

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
