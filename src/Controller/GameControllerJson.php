<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\JsonGameFormat;

class GameControllerJson
{
    #[Route("/api/game")]
    public function apiGame(SessionInterface $session): Response
    {
        $game = new JsonGameFormat($session);
        $data = $game->jsonGame($session);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
