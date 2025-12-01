<?php

namespace App\Project;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Project\Player;

/**
 * Class representing scores in a card game.
 */
class Score
{
    public function __construct()
    {
    }

    /**
     * Returns the score of a player.
     *
     * @param Player $player The player object.
     * @return int The total points of the player.
     */
    public function getScore(Player $player): int
    {
        return $player->points;
    }

    /**
     * Calulates the score of a players hand.
     *
     * @param CardHand $hand The player hand.
     * @return int The total points of the player.
     */
    public function manageScore(CardHand $hand): int
    {
        $totalScore = 0;
        $numAce = 0;
    
        foreach ($hand->hand as $card) {
            $value = $card->getValue();
            if ($value === 1 ) {
                $totalScore += 11;
                $numAce += 1;
            } else if ($value >= 11) {
                $totalScore += 10;
            } else {
                $totalScore += $value;
            }
        }

        while ($totalScore > 21 && $numAce > 0) {
            $totalScore -= 10;
            $numAce -= 1;
        }

        return $totalScore;
    }

    /**
     * Updtaes the score of a players hand.
     *
     * @param Player $player The player object.
     */
    public function updateScore(Player $player, int $score): void
    {
        $player->points = $score;
    }
}
