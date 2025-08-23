<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Game;

/**
 * Class for handling game sessions.
 */
class SessionGameMethods
{
    public function __construct()
    {
    }

    /**
     * Retrieves game data from the session.
     *
     * @param SessionInterface $session for storing game data.
     * @return array An array containing the bank's hand, player's hand, player's score, and bank's score.
     */
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

    /**
     * Retrieves and handles game data from the session.
     *
     * @param SessionInterface $session for storing game data.
     * @return array An array containing the player's score, bank's score, player's hand, bank's hand, and game result.
     */
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

    /**
     * Retrieves and handles game data from the session.
     *
     * @param SessionInterface $session for storing game data.
     * @return array An array containing the player's hand, score, status, bank's hand, bank's score, and game over status.
     */
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
