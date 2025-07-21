<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

class Draw
{
    public function __construct()
    {
    }

    public function playerDraw(SessionInterface $session, $card = null)
    {
        if ($card !== null) {
            $playerHand = $session->get('playerHand');
            $playerHand[] = $card;
            $session->set('playerHand', $playerHand);
        }

        $deck = $session->get('deck');
        $deck = $deck instanceof DeckOfCards ? $deck : new DeckOfCards();
        $turn = new Turn();
        $result = $turn->playerTurn($deck);
        $playerHand = $session->get('playerHand', []);
        if (!is_array($playerHand)) {
            $playerHand = (array) $playerHand;
        }
        $playerHand = array_merge($playerHand, $result['hand']);
        $playerScore = $session->get('playerScore', 0) + $result['score'];
    
        $session->set('deck', $deck);
        $session->set('playerHand', $playerHand);
        $session->set('playerScore', $playerScore);

        return $playerHand;
    }

    public function bankDraw(SessionInterface $session, $card = null): void
    {
        if ($card !== null) {
            $bankHand = $session->get('bankHand');
            $bankHand[] = $card;
            $session->set('bankHand', $bankHand);
        }

        $deck = $session->get('deck');
        $deck = $deck instanceof DeckOfCards ? $deck : new DeckOfCards();
        $turn = new Turn();
        $result = $turn->bankTurn($deck);

        $bankHand = $session->get('bankHand', []);
        if (!is_array($bankHand)) {
            $bankHand = (array) $bankHand;
        }
        $bankHand = array_merge($bankHand, $result['hand']);
        $bankScore = $session->get('bankScore', 0) + $result['score'];

        $session->set('deck', $deck);
        $session->set('bankHand', $bankHand);
        $session->set('bankScore', $bankScore);
    }
}
