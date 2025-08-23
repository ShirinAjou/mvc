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
 * Controller for handling game-related routes and rendering game templates.
 */
class GameController extends AbstractController
{
    /**
     * Renders the main game page.
     *
     * @return Response The rendered game page.
     */
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('card/game.html.twig');
    }

    /**
     * Renders the documentation page for the game.
     *
     * @return Response The rendered documentation page.
     */
    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('card/doc.html.twig');
    }

    /**
     * Starts the game and renders the game page.
     *
     * @return Response The rendered game page.
     */
    #[Route("/game", name: "start_game")]
    public function start(): Response
    {
        return $this->render('card/game.html.twig');
    }

    /**
     * Handles the game play logic, including restarting the game and rendering the play page.
     *
     * @param SessionInterface $session The session interface for storing game data.
     * @param Request $request The HTTP request object.
     * @return Response The rendered play page.
     */
    #[Route("/game/play", name: "play", methods: ["GET", "POST"])]
    public function play(SessionInterface $session, Request $request): Response
    {
        if ($request->request->get('restartCard')) {
            $session->set('playerHand', []);
            $session->set('playerScore', 0);
            $session->set('bankHand', []);
            $session->set('bankScore', 0);
            return $this->redirectToRoute('play');
        }

        $sessionMethods = new SessionGameMethods();
        $sessionMethods->sessionGame($session);
        $returnGame = $sessionMethods->returnGame($session);

        return $this->render('card/play.html.twig', $returnGame);
    }

    /**
     * Handles the drawing of cards for the player and updates the session with the new game state.
     *
     * @param SessionInterface $session The session interface for storing game data.
     * @return Response A redirect to the play route.
     */
    #[Route("/game/draw", name: "draw_game", methods: ["POST"])]
    public function drawGame(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        if (!$deck instanceof DeckOfCards || $deck->countCards() <= 0) {
            $deck = new DeckOfCards();
            $deck->shuffleCards();
        }

        $playerHand = $session->get('playerHand', []);
        $playerScore = $session->get('playerScore', 0);

        $draw = new Draw();
        $result = $draw->playerDraw($playerHand, $deck);

        $session->set('deck', $result['deck']);
        $session->set('playerHand', $result['hand']);
        $session->set('playerScore', $playerScore + (int) $result['score']);

        return $this->redirectToRoute('play');
    }

    /**
     * Handles the bank's turn in the game, updates the session with the new game state, and renders the result page.
     *
     * @param SessionInterface $session The session interface for storing game data.
     * @return Response The rendered result page.
     */
    #[Route("/game/stop", name: "stop_game", methods: ["POST"])]
    public function stopGame(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        if (!$deck instanceof DeckOfCards || $deck->countCards() <= 0) {
            $deck = new DeckOfCards();
            $deck->shuffleCards();
        }

        $bankHand = $session->get('bankHand', []);
        $bankScore = $session->get('bankScore', 0);

        $draw = new Draw();
        $result = $draw->bankDraw($bankHand, $deck);

        $session->set('deck', $result['deck']);
        $session->set('bankHand', $result['hand']);
        $session->set('bankScore', $bankScore + $result['score']);

        $sessionMethods = new SessionGameMethods();
        $data = $sessionMethods->gameData($session);

        return $this->render('card/result.html.twig', $data);
    }
}
