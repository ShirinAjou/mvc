<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Game;

class SessionGameMethods
{
    public function __construct()
    {
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

        return [
            'bankHand' => $bankHand,
            'playerHand' => $playerHand,
            'playerScore' => is_int($playerScore) ? $playerScore : 0,
            'bankScore' => is_int($bankScore) ? $bankScore : 0,
        ];
    }

    public function gameData(SessionInterface $session): array
    {
        $sessionData = $this->sessionGame($session);

        $playerScore = $sessionData['playerScore'];
        $bankScore = $sessionData['bankScore'];
        $playerHand = $sessionData['playerHand'];
        $bankHand = $sessionData['bankHand'];

        $game = new Game($session);
        $resultText = $game->winner($playerScore, $bankScore);

        return [
            'playerScore' => $playerScore,
            'bankScore' => $bankScore,
            'playerHand' => $playerHand,
            'bankHand' => $bankHand,
            'result' => $resultText,
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
}
