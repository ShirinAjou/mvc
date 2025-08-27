<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\JsonGameFormat;

/**
 * Controller for handling JSON routes related to the game.
 */
class GameControllerJson
{
    /**
     * Returns a JSON response with the current game state.
     *
     * @param SessionInterface $session for storing game data.
     * @return Response A JSON response with the current game state.
     */
    #[Route('/api/game', name:'api_game')]
    public function apiGame(SessionInterface $session): Response
    {
        $game = new JsonGameFormat();
        $data = $game->jsonGame($session);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
