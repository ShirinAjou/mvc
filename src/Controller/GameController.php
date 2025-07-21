<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\Game;
use App\Card\Draw;

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
    public function start(SessionInterface $session): Response
    {
        return $this->render('card/game.html.twig');
    }

    #[Route("/game/play", name: "play", methods: ["GET", "POST"])]
    public function play(SessionInterface $session, Request $request): Response
    {
        if ($request->request->get('restartCard')) {
            $game = new Game();
            $game->resetGame($session);
            return $this->redirectToRoute('play');
        }

        $game = new Game();
        $game->sessionGame($session);
        $returnGame = $game->returnGame($session);

        return $this->render('card/play.html.twig', $returnGame);
    }

    #[Route("/game/draw", name: "draw_game", methods: ["POST"])]
    public function drawGame(Request $request, SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        if (!$deck instanceof DeckOfCards || $deck->countCards() <= 0) {
            $deck = new DeckOfCards();
            $deck->shuffleCards();
            $session->set('deck', $deck);
        }
        $draw = new Draw();
        $draw->playerDraw($session);

        return $this->redirectToRoute('play');
    }

    #[Route("/game/stop", name: "stop_game", methods: ["POST"])]
    public function stopGame(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        if (!$deck instanceof DeckOfCards || $deck->countCards() <= 0) {
            $deck = new DeckOfCards();
            $deck->shuffleCards();
            $session->set('deck', $deck);
        }
        $draw = new Draw();
        $draw->bankDraw($session);

        $game = new Game();
        $data = $game->gameData($session);

        return $this->render('card/result.html.twig', $data);
    }
}
