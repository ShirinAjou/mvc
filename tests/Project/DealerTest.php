<?php

namespace App\Tests\Project;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Project\Score;
use App\Project\Dealer;

/**
 * Test cases for Dealer class.
 */
class DealerTest extends TestCase
{
    public function testDealerDraw(): void
    {
        $deck = new DeckOfCards();
        $card = new CardGraphic(2, 1);
        $score = new Score();
        $dealer = new Dealer();

        $result = $dealer->dealerDraw($deck, $score);

        $this->assertGreaterThanOrEqual(17, $dealer->dealerPoints);
    }

    public function testFirstCard(): void
    {
        $deck = new DeckOfCards();
        $score = new Score();
        $dealer = new Dealer();

        $dealer->dealerDraw($deck, $score);
        $firstCard = $dealer->firstCard();

        $this->assertEquals($dealer->dealerHand->hand[0], $firstCard);
        $this->assertNotNull($dealer->dealerHand->hand[0]);
    }

    public function testFirstPoints(): void
    {
        $deck = new DeckOfCards();
        $card = new CardGraphic(2, 1);
        $score = new Score();
        $dealer = new Dealer();

        $dealer->dealerHand->hand[] = $card;
        $result = $dealer->firstPoints($score);

        $this->assertEquals(2, $result);
    }
}
