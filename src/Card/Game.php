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

    public function playGame(DeckOfCards $deck, SessionInterface $session): bool
    {
        $gameOver = false;

        if (isset($_POST['drawCard'])) {
            $turn = new Turn();
            $turn->playerTurn($deck);
        }

        if (isset($_POST['stop'])) {
            $turn = new Turn();
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
        return "Palyer wins";
    }

    public function resetGame(SessionInterface $session): void
    {
        $session->set('playerHand', []);
        $session->set('playerScore', 0);
        $session->set('bankHand', []);
        $session->set('bankScore', 0);
    }

    /**
     * @return array{
     *     bankHand: array<CardGraphic>,
     *     playerHand: array<CardGraphic>,
     *     playerScore: int,
     *     bankScore: int
     * }
     */
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

    /**
     * @return array{
     *     player: array{
     *         hand: array<CardGraphic>,
     *         score: int,
     *         status: string
     *     },
     *     bankScore: int,
     *     bankHand: array<CardGraphic>,
     *     gameOver: bool
     * }
     */
    public function returnGame(SessionInterface $session): array
    {
        $data = $this->sessionGame($session);
        $playerHand = $data['playerHand'];
        $bankHand = $data['bankHand'];
        $playerScore = $data['playerScore'];
        $bankScore = $data['bankScore'];

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

    /**
     * @return array{
     *     player: array{
     *         hand: list<array{value: string, suit: string}>,
     *         score: int
     *     },
     *     bank: array{
     *         hand: list<array{value: string, suit: string}>,
     *         score: int
     *     }
     * }
     */
    public function jsonGame(SessionInterface $session): array
    {
        $data = $this->sessionGame($session);
        $playerHand = $data['playerHand'];
        $bankHand = $data['bankHand'];
        $playerScore = $data['playerScore'];
        $bankScore = $data['bankScore'];

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

    /**
     * @return array{
     *     playerScore: int,
     *     bankScore: int,
     *     playerHand: array<CardGraphic>,
     *     bankHand: array<CardGraphic>,
     *     result: string
     * }
     */
    public function gameData(SessionInterface $session): array
    {
        $data = $this->sessionGame($session);

        $playerScore = $data['playerScore'];
        $bankScore = $data['bankScore'];

        $result = $this->winner($playerScore, $bankScore);

        return [
            'playerScore' => $playerScore,
            'bankScore' => $bankScore,
            'playerHand' => $data['playerHand'],
            'bankHand' => $data['bankHand'],
            'result' => $result,
        ];
    }
}
