<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Card\Turn;

class Game
{
    public function __construct()
    {
    }

    public function playGame(DeckOfCards $deck, SessionInterface $session, Turn $turn, bool $drawCard, bool $stop): bool
    {
        $gameOver = false;

        if ($drawCard) {
            $turn->playerTurn($deck);
        }

        if ($stop) {
            $turn->bankTurn($deck);
        }

        $gameOver = true;
        $this->resetGame($session);

        return $gameOver;
    }

    public function winner(int $playerScore, int $bankScore): string
    {
        if ($playerScore > 21) {
            return "Bank wins";
        }

        if ($bankScore > 21) {
            return "Player wins";
        }

        if ($bankScore >= $playerScore) {
            return "Bank wins";
        }
        return "Player wins";
    }

    public function resetGame(SessionInterface $session): void
    {
        $session->set('playerHand', []);
        $session->set('playerScore', 0);
        $session->set('bankHand', []);
        $session->set('bankScore', 0);
    }

    public function sessionGame(SessionInterface $session): array
    {
        $bankHand = $session->get('bankHand', []);
        $playerHand = $session->get('playerHand', []);
        $playerScore = $session->get('playerScore', 0);
        $bankScore = $session->get('bankScore', 0);

        /** @var array<CardGraphic> $bankHand */
        $bankHand = is_array($bankHand) ? $bankHand : [];

        /** @var array<CardGraphic> $playerHand */
        $playerHand = is_array($playerHand) ? $playerHand : [];

        return [
            'bankHand' => $bankHand,
            'playerHand' => $playerHand,
            'playerScore' => is_int($playerScore) ? $playerScore : 0,
            'bankScore' => is_int($bankScore) ? $bankScore : 0,
        ];
    }

    public function returnGame(SessionInterface $session): array
    {
        $result = $this->sessionGame($session);
        $playerHand = $result['playerHand'];
        $bankHand = $result['bankHand'];
        $playerScore = $result['playerScore'];
        $bankScore = $result['bankScore'];

        $playerStatus = $playerScore > 21 ? 'lose' : 'playing';
        $gameOver = $playerScore > 21;

        return [
            'player' => [
                'hand' => $playerHand,
                'score' => $playerScore,
                'status' => $playerStatus
            ],
            'bankScore' => $bankScore,
            'bankHand' => $bankHand,
            'gameOver' => $gameOver
        ];
    }

    public function jsonGame(SessionInterface $session): array
    {
        $result = $this->sessionGame($session);
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

    // public function gameData(SessionInterface $session): array
    // {
    //     $result = $this->sessionGame($session);

    //     $playerScore = $result['playerScore'];
    //     $bankScore = $result['bankScore'];

    //     $result = $this->winner($playerScore, $bankScore);

    //     return [
    //         'playerScore' => $playerScore,
    //         'bankScore' => $bankScore,
    //         'playerHand' => $result['playerHand'],
    //         'bankHand' => $result['bankHand'],
    //         'result' => $result,
    //     ];
    // }

    public function gameData(SessionInterface $session): array
    {
        $sessionData = $this->sessionGame($session);

        $playerScore = $sessionData['playerScore'];
        $bankScore = $sessionData['bankScore'];
        $playerHand = $sessionData['playerHand'];
        $bankHand = $sessionData['bankHand'];

        $resultText = $this->winner($playerScore, $bankScore);

        return [
            'playerScore' => $playerScore,
            'bankScore' => $bankScore,
            'playerHand' => $playerHand,
            'bankHand' => $bankHand,
            'result' => $resultText,
        ];
    }
}
