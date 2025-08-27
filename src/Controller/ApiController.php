<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\Draw;
use App\Card\SessionGameMethods;

/**
 * Controller for handling API-related routes.
 */
class ApiController extends AbstractController
{
    /**
     * Returns a JSON response with all available api.
     *
     * @return Response A JSON response listing api.
     */
    #[Route("/api", name: "api")]
    public function apiIndex(): Response
    {
        return $this->render('api.html.twig');
    }
}
