<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Draw;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;

/**
 * Test cases for class Draw.
 */
class DrawTest extends TestCase
{
    public function testPlayerDraw(): void
    {
        $deck = new DeckOfCards();
        $drawncard = $deck->getCards();
        $topCard = $drawncard[0];
        $playerHand = [];
        $draw = new Draw();
        $result = $draw->playerDraw($playerHand, $deck);

        $this->assertContains($topCard, $result['hand']);
    }

    public function testBankDraw(): void
    {
        $deck = new DeckOfCards();
        $card = new CardGraphic(2, 1);
        $bankHand = [];
        $bankHand[] = $card;

        $draw = new Draw();
        $result = $draw->bankDraw($bankHand, $deck);

        $this->assertGreaterThanOrEqual(17, $result['score']);
    }
}
