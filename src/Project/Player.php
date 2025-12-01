<?php

namespace App\Project;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardHand;

/**
 * Class representing the players in a card game.
 */
class Player
{
    public string $name;
    public int $points;
    public CardHand $hand;
    public string $status;
    public bool $drawnCard;
    public string $message;
    
    /**
     *
     * New player starts with a given name and sets default values for player.
     *
     * @param string $name The name of the player.
     */
    public function __construct($name = "")
    {
        $this->name = $name;
        $this->points = 0;
        $this->hand = new CardHand();
        $this->status = 'playing';
        $this->drawnCard = false;
        $this->message = "";
    }

    /**
     * Handles ending the game when all players have finished playing. 
     * 
     * @param array $players Array of players in the game.
     * @return string The name of the player.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Handles stand button, when a player does not want to draw more cards. 
     *
     */
    public function standButton(): void 
    {
        $this->status = "stand";
    }

    /**
     * Handles player  draws card from the deck.
     * 
     * @param DeckOfCards $deck The deck to draw a card from.
     * @param Score $scoreManage Calculating points.
     */
    public function playerDraw(DeckOfCards $deck, Score $scoreManager): void 
    {
        $currentScore = $scoreManager->manageScore($this->hand);

        if ($currentScore >= 21) {
            $this->status = "lose";
            return;
        }

        if ($currentScore === 21) {
            $this->status = 'stand';
        }

        if ($deck->countCards() > 0) {
            $this->hand->drawCard($deck);
            $this->drawnCard = true;
            $this->message = "";
        }
    }
}
