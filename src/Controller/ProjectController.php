<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Card\DeckOfCards;
use App\Project\Player;
use App\Project\Score;
use App\Project\Dealer;
use App\Project\Game;

/**
 * Controller for handling project blackjack routes.
 */
final class ProjectController extends AbstractController
{
    /**
     * Renders the project index page.
     *
     * @return Response The rendered index page.
     */
    #[Route('/proj', name: 'proj')]
    public function index(): Response
    {
        return $this->render('proj/index.html.twig');
    }

    /**
     * Renders the about page.
     *
     * @return Response The rendered about page.
     */
    #[Route('/proj/about', name: 'proj_about')]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    /**
     * Returns a JSON response with all available api.
     *
     * @return Response A JSON response listing api.
     */
    #[Route("/proj/api", name: "proj_api")]
    public function api(SessionInterface $session): Response
    {
        $players = $session->get('namePlayers', []);
        return $this->render('proj/api.html.twig', [
            'players' => $players
        ]);
    }

    /**
     * Renders the game page.
     *
     * @return Response The rendered game page.
     */
    #[Route('/proj/init', name: 'init_game', methods: ['POST', 'GET'])]
    public function initGame(SessionInterface $session, Request $request): Response
    {
        $numPlayers = $request->get('number');
        $gamePlayers = new Game();
        $namePlayers = $gamePlayers->numberPlayers($numPlayers);
        
        $gameDeck = new DeckOfCards();
        $gameDeck->shuffleCards(); 
        $playerIndex = 0;

        $playerScore = new Score();
        $dealer = new Dealer();
        $gamePlayers->twoCards($gameDeck, $playerScore);
        $dealer->dealerDraw($gameDeck, $playerScore);

        $session->set('gamePlayers', $gamePlayers);
        $session->set('dealer', $dealer);
        $session->set('dealerCard', $dealer->firstCard());
        $session->set('namePlayers', $namePlayers);
        $session->set('gameDeck', $gameDeck);
        $session->set('playerIndex', $playerIndex);

        return $this->redirectToRoute('play_proj');
    }

    /**
     * Renders the game page.
     *
     * @return Response The rendered game page.
     */
    #[Route('/proj/play', name: 'play_proj', methods: ['POST', 'GET'])]
    public function playGame(SessionInterface $session): Response
    {
        $deck = $session->get('gameDeck');
        if (!$deck instanceof DeckOfCards || $deck->countCards() <= 0) {
            $deck = new DeckOfCards();
            $deck->shuffleCards();
            $session->set('gameDeck', $deck);
        }

        $scoreManager = new Score();
        $dealer = $session->get('dealer');

        $players = $session->get('namePlayers');
        $playerIndex = $session->get('playerIndex', 0);
        $currentPlayer = $players[$playerIndex];

        return $this->render('proj/play.html.twig', [
            'players' => $players,
            'playerIndex' => $playerIndex,
            'currentPlayer' => $currentPlayer,
            'messages' => $session->get('messages', ""),
            'cards' => $session->get('cardsArray', []),
            'score' => $session->get('score'),
            'dealerCard' => $dealer->firstCard(),
            'dealerScore' => $dealer->firstPoints($scoreManager),
        ]);
    }

    /**
     * Renders the game page.
     *
     * @return Response The rendered game page.
     */
   #[Route('/proj/draw', name: 'draw_proj', methods: ['POST', 'GET'])]
    public function drawGame(SessionInterface $session, Request $request): Response
    { 
        $namePlayers = $session->get('namePlayers');
        $gameDeck = $session->get('gameDeck');
        $playerIndex = $session->get('playerIndex', 0);
        $game = $session->get('gamePlayers');
        
        if ($request->get('drawCard')) {      
            $playerScore = new Score();
            $player = $namePlayers[$playerIndex];

            $player->playerDraw($gameDeck, $playerScore);
            $score = $playerScore->manageScore($player->hand);
            $playerScore->updateScore($player, $score);

            $nextIndex = $game->playerTurn($namePlayers, $playerScore, $playerIndex);
            if ($nextIndex === null) {
                return $this->redirectToRoute('result_proj');
            }

            $session->set('playerIndex', $nextIndex);
            $session->set('namePlayers', $namePlayers);
            $session->set('gamePlayers', $game);
        }
        return $this->redirectToRoute('play_proj');
    }

    /**
     * Renders the game page.
     *
     * @return Response The rendered game page.
     */
    #[Route('/proj/stand', name: 'stand_proj', methods: ['POST', 'GET'])]
    public function standGame(SessionInterface $session, Request $request): Response
    { 
        $namePlayers = $session->get('namePlayers');
        $playerIndex = $session->get('playerIndex', 0);
        $game = $session->get('gamePlayers');

        $player = $namePlayers[$playerIndex];
        $nextIndex = $playerIndex;
        $player->standButton();

        $playerScore = new Score();
        $nextIndex = $game->playerTurn($namePlayers, $playerScore, $playerIndex);

        if ($nextIndex === null) {
            return $this->redirectToRoute('result_proj');
        }
        $session->set('playerIndex', $nextIndex);
        $session->set('namePlayers', $namePlayers);
        $session->set('gamePlayers', $game);

        return $this->redirectToRoute('play_proj');
    }

    /**
     * Renders the result page.
     *
     * @return Response The rendered result page.
     */
    #[Route('/proj/result', name: 'result_proj', methods: ['POST', 'GET'])]
    public function resultGame(SessionInterface $session, Request $request): Response
    {
        $game = $session->get('gamePlayers');
        $players = $session->get('namePlayers');
        $dealer = $session->get('dealer');

        $scoreManager = new Score();
        $dealerScore = $scoreManager->manageScore($dealer->dealerHand);
        $winner = $game->getWinner($players, $dealer);

        return $this->render('proj/result.html.twig', [
            'players' => $players,
            'dealer' => $dealer,
            'dealerScore' => $dealerScore,
            'winner' => $winner,
        ]);
    }
}
