<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Project\JsonCardGameFormat;

/**
 * Controller for handling JSON responses related to the project.
 */
class ProjectControllerJson extends AbstractController
{
    /**
     * Returns a JSON response with the current game state including points.
     * 
     * @return Response A JSON response with scores.
     */
    #[Route('/proj/api/score', name:'api_score', methods: ['GET'])]
    public function apiGame(SessionInterface $session): Response
    {
        $jsonFormat = new JsonCardGameFormat();
        $data = $jsonFormat->jsonGame($session);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Returns a JSON response with the current players.
     *
     * @return Response A JSON response with players.
     */
    #[Route('/proj/api/players', name:'api_players', methods: ['GET'])]
    public function apiPlayers(SessionInterface $session): Response
    {
        $jsonFormat = new JsonCardGameFormat();
        $data = $jsonFormat->jsonPlayer($session);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Returns a JSON response with dealer data.
     *
     * @return Response A JSON response with the dealer.
     */
    #[Route('/proj/api/dealer', name:'api_dealer', methods: ['GET'])]
    public function apiDealer(SessionInterface $session): Response
    {
        $jsonFormat = new JsonCardGameFormat();
        $data = $jsonFormat->jsonDealer($session);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Returns a JSON response with the current players status.
     *
     * @return Response A JSON response with player status.
     */
    #[Route('/proj/api/status', name:'api_status', methods: ['GET'])]
    public function apiStatus(SessionInterface $session): Response
    {
        $jsonFormat = new JsonCardGameFormat();
        $data = $jsonFormat->jsonStatus($session);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Returns a JSON response with the details of chosen player.
     *
     * @return Response A JSON response with the players's details.
     */
    #[Route('/proj/api/player/details', name:'api_details', methods: ['POST', 'GET'])]
    public function apiDetails(SessionInterface $session, Request $request): Response
    {
        $playerName = $request->query->get('player', '');
        
        $jsonFormat = new JsonCardGameFormat();
        $data = $jsonFormat->jsonDetails($session, $playerName);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
