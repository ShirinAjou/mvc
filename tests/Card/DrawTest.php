<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Draw;
use App\Card\DeckOfCards;

/**
 * Test cases for class Draw.
 */
class DrawTest extends TestCase
{
    /**
     * 
     */
    public function testplayerDraw()
    {
        $session = $this->createMock(SessionInterface::class);

        $session->expects($this->exactly(3))
            ->method('set')
            ->withConsecutive(
                ['deck', $this->anything()],
                ['playerHand', $this->anything()],
                ['playerScore', $this->anything()]
            );

        $draw = new Draw();
        $draw->playerDraw($session);

        $this->assertTrue(true);
    }

    public function testplayerCount()
    {
        $session = $this->createMock(SessionInterface::class);

        $session->expects($this->exactly(3))
            ->method('set')
            ->withConsecutive(
                ['deck', $this->anything()],
                ['playerHand', $this->anything()],
                ['playerScore', $this->anything()]
            );

        $session->method('get')
            ->willReturnCallback(function ($key) {
                $value = [
                    'playerHand' => ['card1', 'card2'],
                ][$key] ?? null;
                return $value;
            });

        $draw = new Draw();
        $draw->playerDraw($session);

        $this->assertCount(2, $session->get('playerHand'));
    }

    /**
     * 
     */
    // public function testPlayerDrawContains() {
    //     $session = $this->createMock(SessionInterface::class);
    //     $session->method('get')->willReturnMap([
    //         ['playerHand', ['card1', 'card2']],
    //     ]);

    //     $draw = new Draw();
    //     $drawncard = $draw->playerDraw($session, 'card3');

    //     $this->assertContains('card3', $drawncard);

    // }

    // public function testBankDrawWithNullCard() 
    // {
    //     $session = $this->createMock(SessionInterface::class);
    //     $session->expects($this->any())->method('get')->with('bankHand')->willReturn([]);
    //     $card = null;
    //     $draw = new Draw();
    //     $session->expects($this->any())->method('get')->with('deck')->willReturn([]);
    //     $draw->bankDraw($session, $card);
    //     $this->assertEquals([], $session->get('bankHand'));
    // }

// assertEquals: Du kan använda denna för att kontrollera att spelarens poäng har uppdaterats korrekt efter en tur.

// assertContains: Du kan använda denna för att kontrollera att ett specifikt kort finns i spelarens hand efter en tur.

// assertCount: Du kan använda denna för att kontrollera antalet kort i spelarens hand eller i kortleken efter en tur.

// assertTrue/assertFalse: Du kan använda dessa för att kontrollera om ett visst villkor är uppfyllt, till exempel om spelaren har vunnit eller förlorat efter en tur.

}
