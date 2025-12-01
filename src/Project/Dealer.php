<?php

namespace App\Project;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Project\Score;

/**
 * Class representing the dealer in a card game.
 */
class Dealer
{
    public int $dealerPoints;
    public CardHand $dealerHand;

    /**
     * Dealer start with 0 points and an empty hand.
     */
    public function __construct()
    {
        $this->dealerPoints = 0;
        $this->dealerHand = new CardHand();
    }

    /**
     * Dealer draws card from the deck until it reaches 17 points.
     * 
     * @param DeckOfCards $deck The deck to draw a card from.
     * @param Score $scoreManager Manages score calculation.
     */
    public function dealerDraw(DeckOfCards $deck, Score $scoreManager): void 
    {
        while ($this->dealerPoints < 17 && $deck->countCards() > 0) {
            $this->dealerHand->drawCard($deck);
            $this->dealerPoints = $scoreManager->manageScore($this->dealerHand);
        }
    }

    /**
     * Handles showing dealers first drawn card to other players.
     * 
     * @return CardGraphic The CardGraphic object.
    */
    public function firstCard(): ?CardGraphic
    {
        if (isset($this->dealerHand->hand[0])) {
            return $this->dealerHand->hand[0];
        }

        return null;
    }

    /**
     * Handles calculationg only the points of the first drawn dard.
     * 
     * @param Score $scoreManager Managaes score calculation.
     * @return int The point of the drawn card.
     */
    public function firstPoints(Score $scoreManager): int
    {
        $firstCardHand = new CardHand();
        $firstCardHand->hand[] = $this->dealerHand->hand[0];
        return $scoreManager->manageScore($firstCardHand);
    }
}
