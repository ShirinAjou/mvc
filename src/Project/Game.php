<?php

namespace App\Project;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Project\Player;
use App\Project\Score;

/**
 * Class representing the game logic in a card game.
 */
class Game
{
    public array $players;

    /**
     * Starts a new game with an empty array of players.
     */
    public function __construct()
    {
        $this->players = [];
    }

    /**
     * Handles the number of players in the card game.
     * 
     * @param int $number Number of players.
     * @return array The array with the correct number of hands and their names.
     */
    public function numberPlayers(int $number): array
    {
        for ($i = 0; $i < $number; $i++) {
            $this->players[] = new Player("Player " . ($i + 1));
        }
        return $this->players;
    }

    /**
     * Handles which players turn is next.
     * 
     * @param array $players Array of players in the game.
     * @param Score $scoreManage Calculating points.
     * @param int $index Index of current pleyrs turn.
     * @return int Index of the next player whose turn it is.
     */
    public function playerTurn(array $players, Score $scoreManager, int $currentIndex): ?int
    {
        $playerCount = count($players);

        for ($i = 1; $i <= $playerCount; $i++) {
            $index = ($currentIndex + $i) % $playerCount;
            $player = $players[$index];

            $score = $scoreManager->manageScore($player->hand);
            $player->points = $score;

            if ($score > 21) {
                $player->status = "lose";
            } elseif ($score === 21) {
                $player->status = "stand";
            }

            if ($player->status === "playing") {
                return $index;
            }
        }
        return null;
    }

    /**
     * Calculates the winner of the card game.
     * 
     * @param array $players Array of players in the game.
     * @param Dealer $dealer The dealer object in the game.
     * @return array Array with winner/winners.
     */
    public function getWinner(array $players, Dealer $dealer) : array
    {
        $winner = [];
        $highestScore = 0;

        $score = new Score;
        $dealerScore = $score->manageScore($dealer->dealerHand);
        
        foreach ($players as $player) {
            $playerScore = $score->manageScore($player->hand);

            if ($playerScore <= 21) {
                if ($playerScore > $highestScore) {
                    $highestScore = $playerScore;
                    $winner = [$player->name];
                } elseif ($playerScore == $highestScore) {
                    $winner[] = $player->name;
                }
            }
        }

        if ($dealerScore <= 21) {
            if ($dealerScore > $highestScore) {
                $winner = ["Dealer"];
            } elseif ($dealerScore == $highestScore) {
                $winner[] = "Dealer";
            }
        }

        if (empty($winner)) {
            return ["Ingen vinnare"];
        }
        return $winner;
    }

    /**
     * Handles ending the game when all players have finished playing. 
     * 
     * @param array $players Array of players in the game.
     * @return bool True if the game has ended.
     */
    public function endGame(array $players): bool
    {
        foreach ($players as $player) {
            if ($player->status === 'playing') {
                return false;
            }
        }
        return true;
    }

    /**
     * Handles showing players first two drawn card to other players.
     * 
     * @param DeckOfCards $deck The deck to draw a card from.
     * @param Score $scoreManage Calculating points.
     */
    public function twoCards(DeckOfCards $deck, Score $scoreManager): void
    {
        foreach ($this->players as $player) {
            $player->hand->drawCard($deck);
            $player->hand->drawCard($deck);

            $score = $scoreManager->manageScore($player->hand);
            $scoreManager->updateScore($player, $score);
        }
    }
}