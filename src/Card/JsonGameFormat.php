<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\SessionGameMethods;

class JsonGameFormat
{
    public function __construct()
    {
    }

    public function jsonGame(SessionInterface $session): array
    {
        $game = new SessionGameMethods();
        $result = $game->sessionGame($session);

        $playerHand = $result['playerHand'];
        $bankHand = $result['bankHand'];
        $playerScore = $result['playerScore'];
        $bankScore = $result['bankScore'];

        $playerCards = [];
        foreach ($playerHand as $card) {
            $playerCards[] = [
                'value' => match ($card->getValue()) {
                    1 => 'A',
                    11 => 'J',
                    12 => 'Q',
                    13 => 'K',
                    default => (string) $card->getValue()
                },
                'suit' => $card->getSuitAsWord()
            ];
        }

        $bankCards = [];
        foreach ($bankHand as $card) {
            $bankCards[] = [
                'value' => match ($card->getValue()) {
                    1 => 'A',
                    11 => 'J',
                    12 => 'Q',
                    13 => 'K',
                    default => (string) $card->getValue()
                },
                'suit' => $card->getSuitAsWord()
            ];
        }

        return [
            'player' => [
                'hand' => $playerCards,
                'score' => $playerScore,
            ],
            'bank' => [
                'hand' => $bankCards,
                'score' => $bankScore,
            ]
        ];
    }
}
