<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\Turn;

class Game
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function playGame(DeckOfCards $deck, Turn $turn, bool $drawCard, bool $stop): bool
    {
        if ($drawCard) {
            $turn->playerTurn($deck);
        }

        if ($stop) {
            $turn->bankTurn($deck);
        }

        $this->session->set('playerHand', []);
        $this->session->set('playerScore', 0);
        $this->session->set('bankHand', []);
        $this->session->set('bankScore', 0);

        return true;
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
}
