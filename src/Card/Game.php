<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\Turn;

/**
 * Class handling game logic.
 */
class Game
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Plays a round of the game.
     *
     * @param DeckOfCards $deck The deck of cards used in the game.
     * @param Turn $turn The current turn in the game.
     * @param bool $drawCard Whether the player draws a card.
     * @param bool $stop Whether the player stops their turn.
     * @return bool True if the game round is completed.
     */
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

    /**
     * Determines the winner of the game based on scores.
     *
     * @param int $playerScore The player's score.
     * @param int $bankScore The bank's score.
     * @return string The winner of the game with a string.
     */
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
