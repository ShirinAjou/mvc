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
    public function testPlayerDraw()
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

    public function testPlayerCount()
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
}
