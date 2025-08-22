<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\Game;
use App\Card\Draw;

/**
 * Controller for card-related routes.
 */
class CardController extends AbstractController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Display the home page for the card application.
     *
     * @return Response The response.
     */
    #[Route("/card", name: "card")]
    public function home(): Response
    {
        return $this->render('card/card.html.twig');
    }

    /**
     * Display the deck of cards.
     *
     * @return Response The response.
     */
    #[Route("/card/deck", name: "deck")]
    public function deck(): Response
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();
        return $this->render('card/deck.html.twig', ['cards' => $cards]);
    }

    /**
     * Shuffle the deck of cards.
     *
     * @param SessionInterface $session The session interface.
     * @return Response The response.
     */
    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $cards = $deck->shuffleCards();
        $session->set('deck', $deck);
        return $this->render('card/shuffle.html.twig', ['cards' => $cards]);
    }

    /**
     * Display the form to draw a card from the deck.
     *
     * @param SessionInterface $session The session interface.
     * @return Response The response.
     */
    #[Route("/card/deck/draw", name: "draw_form", methods: ["GET"])]
    public function drawForm(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $cardsLeft = $deck->countCards();

        return $this->render('card/draw.html.twig', [
            'card' => null,
            'cardsLeft' => $cardsLeft,
        ]);
    }

    /**
     * Draw a card from the deck.
     *
     * @param Request $request The request object.
     * @param SessionInterface $session The session interface.
     *
     * @return Response The response.
     */
    #[Route("/card/deck/draw", name: "draw", methods: ["POST"])]
    public function draw(Request $request, SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $card = null;
        $cardsLeft = $deck->countCards();

        if ($request->request->get('drawCard') && $cardsLeft > 0) {
            $hand = new CardHand();
            $card = $hand->drawCard($deck);
            $cardsLeft = $deck->countCards();
        }

        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'card' => $card,
            'cardsLeft' => $cardsLeft,
        ]);
    }

    /**
     * Draw a specified number of cards from the deck.
     *
     * @param int $number The number of cards to draw.
     * @param SessionInterface $session The session interface.
     *
     * @return Response The response.
     */
    #[Route("/card/deck/draw/{number<\d+>}", name: "draw_number")]
    public function drawNumber(int $number, SessionInterface $session): Response
    {
        $drawnCards = [];
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }

        $cardsLeft = $deck->countCards();
        if ($number > $cardsLeft) {
            $number = $cardsLeft;
        }

        for ($i = 0; $i < $number; $i++) {
            $hand = new CardHand();
            $card = $hand->drawCard($deck);
            array_push($drawnCards, $card);
        }

        $cardsLeft = $deck->countCards();
        $session->set('deck', $deck);

        return $this->render('card/draw_number.html.twig', [
            'drawnCards' => $drawnCards,
            'cardsLeft' => $cardsLeft,
        ]);
    }

    /**
     * Display the form to shuffle the deck of cards.
     *
     * @return Response The response.
     */
    #[Route("/card/shuffle/form", name: "form_shuffle", methods: ["GET"])]
    public function shuffleForm(): Response
    {
        return $this->render('card/shuffle_form.html.twig');
    }

    #[Route("/session", name: "session_start")]
    public function session(): Response
    {
        $session = $this->requestStack->getSession();

        return $this->render('card/session.html.twig', [
        'sessionData' => $session->all()]);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function delete(): Response
    {
        $session = $this->requestStack->getSession();
        $session->clear();
        $message = "Nu är sessionen raderad";

        return $this->render('card/delete.html.twig', [
        'message' => $message]);
    }
}
