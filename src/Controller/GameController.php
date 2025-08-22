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

class GameController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('card/game.html.twig');
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('card/doc.html.twig');
    }

    #[Route("/game", name: "start_game")]
    public function start(): Response
    {
        return $this->render('card/game.html.twig');
    }

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

        $sessionMethods = new SessionGameMethods($session);
        $data = $sessionMethods->gameData($session);

        return $this->render('card/result.html.twig', $data);
    }
}
