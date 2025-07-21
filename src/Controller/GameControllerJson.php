<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\Game;

class GameControllerJson
{
    // #[Route("/api")]
    // public function jsonNumber(): Response
    // {
    //     $data = [
    //         'quote' => '/api/quote',
    //         'show deck' => '/api/deck',
    //         'shuffle deck' => '/api/deck/shuffle',
    //         'draw card' => '/api/deck/draw',
    //         'draw card {number}' => '/api/deck/draw/{number}',
    //         'show current score' => '/api/game'
    //     ];

    //     $response = new JsonResponse($data);
    //     $response->setEncodingOptions(
    //         $response->getEncodingOptions() | JSON_PRETTY_PRINT
    //     );
    //     return $response;
    // }

    #[Route("/api/game")]
    public function apiGame(SessionInterface $session): Response
    {
        $game = new Game();
        $data = $game->jsonGame($session);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
