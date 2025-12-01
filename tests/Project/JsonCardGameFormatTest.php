<?php

namespace App\Tests\Project;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Project\Player;
use App\Project\Score;
use App\Project\Dealer;
use App\Project\JsonCardGameFormat;

/**
 * Test cases for JsonCardGameFormat class.
 */
class JsonCardGameFormatTest extends TestCase
{
    public function testJsonGame()
    {
        $sessionMock = $this->createMock(SessionInterface::class);

        $sessionMock->method("get")
        ->withConsecutive(["namePlayers"], ["dealer"])
        ->willReturnOnConsecutiveCalls([new Player("Player 1")], new Dealer());

        $jsonFormat = new JsonCardGameFormat();

        $result = $jsonFormat->jsonGame($sessionMock);
        $expected = [
            "players" => [
                ["name" => "Player 1", "score" => 0]
            ],
            "dealer" => [
                "score" => 0
                ]
            ];

        $this->assertEquals($expected, $result);
    }

    public function testJasonPlayer()
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $jsonFormat = new JsonCardGameFormat();

        $sessionMock->method("get")->with("namePlayers", [])->willReturn([
            new Player("Player 1"),
            new Player("Player 2"),
            new Player("Player 3"),
        ]);

        $result = $jsonFormat->jsonPlayer($sessionMock);

        $expected = [
            "players" => [
                ["name" => "Player 1"],
                ["name" => "Player 2"],
                ["name" => "Player 3"]
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testJasonDealer()
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $jsonFormat = new JsonCardGameFormat();
        $dealer = new Dealer();

        $sessionMock->method("get")
        ->with("dealer")
        ->willReturn(new Dealer());
       
        $result = $jsonFormat->jsonDealer($sessionMock);

        $expected = [
            "dealer" => [
                "hand" => $dealer->dealerHand->getCardsForJson(),
                "score" => $dealer->dealerPoints
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testJsonStatus()
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $jsonFormat = new JsonCardGameFormat();

        $sessionMock->method("get")->with("namePlayers", [])->willReturn([
            new Player("Player 1"),
            new Player("Player 2"),
        ]);

        $result = $jsonFormat->jsonStatus($sessionMock);

        $expected = [
            "players" => [
                ["status" => "playing"],
                ["status" => "playing"]
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testJsonDetails()
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $jsonFormat = new JsonCardGameFormat();

        $player1 = new Player("Player 1");
        $player2 = new Player("Player 2");

        $sessionMock->method("get")->with("namePlayers", [])->willReturn([
            $player1,
            $player2
        ]);

        $result = $jsonFormat->jsonDetails($sessionMock, "Player 1");

        $expected = [
            "player" => [
                "name" => $player1->name,
                "status" => $player1->status,
                "hand" => $player1->hand->getCardsForJson(),
                "score" => $player1->points
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testJsonDetailsError()
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $jsonFormat = new JsonCardGameFormat();

        $sessionMock->method("get")->willReturn([
            new Player("Player 1"),
            new Player("Player 2")
        ]);

        $result = $jsonFormat->jsonDetails($sessionMock, "Player 4");

        $expected = [
            "message" => "No player found"
        ];
        $this->assertEquals($expected, $result);
    }
}