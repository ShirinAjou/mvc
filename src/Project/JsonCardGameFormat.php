<?php

namespace App\Project;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardHand;
use App\Project\Player;

/**
 * Class that formats game data for JSON output.
 */
class JsonCardGameFormat
{
    public function __construct()
    {
    }

    /**
     * Formats game data from a session to a JSON format.
     *
     * @param SessionInterface $session The session containing game data.
     * @return array The game data in an array.
     */
    public function jsonGame(SessionInterface $session): array
    {
         $players = $session->get('namePlayers', []);
        $dealer = $session->get('dealer', new Dealer());

        $playerData = [];
        foreach ($players as $player) {
            $playerData[] = [
                "name" => $player->name,
                "score" => $player->points
            ];
        }

        return [
            "players" => $playerData,
            "dealer" => [
                "score" => $dealer->dealerPoints
            ]
        ];
    }

    /**
     * Formats player data from a session to a JSON format.
     *
     * @param SessionInterface $session The session containing player data.
     * @return array The player data in an array.
     */
    public function jsonPlayer(SessionInterface $session): array
    {
        $players = $session->get('namePlayers', []);

        $playerData = [];
        foreach ($players as $player) {
            $playerData[] = [
                "name" => $player->name,
            ];
        }

        return [
            "players" => $playerData,
        ];
    }

    /**
     * Formats dealer data from a session to a JSON format.
     *
     * @param SessionInterface $session The session containing dealer data.
     * @return array The dealer data in an array.
     */
    public function jsonDealer(SessionInterface $session): array
    {
        $dealer = $session->get('dealer', new Dealer());

        return [
            "dealer" => [
                "hand" => $dealer->dealerHand->getCardsForJson(),
                "score" => $dealer->dealerPoints
            ]
        ];
    }

    /**
     * Formats player status data from a session to a JSON format.
     *
     * @param SessionInterface $session The session containing player data.
     * @return array The player status data in an array.
     */
    public function jsonStatus(SessionInterface $session): array
    {
        $players = $session->get('namePlayers', []);

        $playerData = [];
        foreach ($players as $player) {
            $playerData[] = [
                "status" => $player->status, 
            ];
        }

        return [
            "players" => $playerData,
        ];
    }

    /**
     * Formats player details data from a session to a JSON format.
     *
     * @param SessionInterface $session The session containing player data.
     * @return array The player details data in an array.
     */
public function jsonDetails(SessionInterface $session, string $playerName): array
{
    $players = $session->get('namePlayers', []);

    foreach ($players as $player) {
        if ($player->name === $playerName) {

            return [
                "player" => [
                    "name" => $player->name,
                    "status" => $player->status,
                    "hand" => $player->hand->getCardsForJson(),
                    "score" => $player->points
                ]
            ];
        }
    }

    return [
        "message" => "No player found"
    ];
}

}
