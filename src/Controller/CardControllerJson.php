<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\JsonCardFormat;

/**
 * Controller for handling JSON card decks.
 */
class CardControllerJson
{
    /**
     * Returns sorted deck of cards as a JSON response.
     *
     * @return Response A JSON response with the sorted deck of cards.
     */
    #[Route('/api/deck', name: 'card_json')]
    public function deckJson(): Response
    {
        $deck = new DeckOfCards();
        $deck->sortCards();
        $cardsArray = $deck->getCards();

        $jsonFormat = new JsonCardFormat($cardsArray);
        $cards = $jsonFormat->getCardsForJson();

        $response = new JsonResponse($cards);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Returns shuffled deck of cards as a JSON response.
     *
     * @param SessionInterface $session for storing the deck.
     * @return JsonResponse A JSON response with the shuffled deck of cards.
     */
    #[Route('/api/deck/shuffle', name: 'card_shuffle', methods: ['POST', 'GET'])]
    public function shuffleDeck(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffleCards();
        $cardsArray = $deck->getCards();
        $session->set('deck', $deck);

        $jsonFormat = new JsonCardFormat($cardsArray);
        $data = ['deck' => $jsonFormat->getCardsForJson()];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Returns the drawn card as a JSON response.
     *
     * @param SessionInterface $session for storing the deck.
     * @return JsonResponse A JSON response with the drawn card details.
     */
    #[Route('/api/deck/draw', name: 'card_draw', methods: ['POST', 'GET'])]
    public function cardDraw(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $card = $deck->drawReturn();
        $suitName = $card->getSuitAsWord();
        $data = [
            'value' => match ($card->getValue()) {
                1 => 'A',
                11 => 'J',
                12 => 'Q',
                13 => 'K',
                default => (string) $card->getValue()
            },
            'suit' => $suitName,
            'cards left' => $deck->countCards()
        ];

        $session->set('deck', $deck);
        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Draws a specified number of cards from the deck and returns card(s) in JSON format.
     *
     * @param SessionInterface $session for storing the deck.
     * @param int $number of cards to draw.
     * @return JsonResponse A JSON response with the drawn card details and the number of cards left in the deck.
     */
    #[Route('/api/deck/draw/{number}', name: 'draw_cards', methods: ['POST', 'GET'])]
    public function drawNumber(SessionInterface $session, int $number): JsonResponse
    {
        $deck = $session->get('deck');
        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
        }
        $drawnCards = [];

        for ($i = 0; $i < $number; $i++) {
            $card = $deck->drawReturn();
            $drawnCards[] = [
                'value' => match ($card->getValue()) {
                    1 => 'A',
                    11 => 'J',
                    12 => 'Q',
                    13 => 'K',
                    default => (string) $card->getValue()
                },
                'suit' => $card->getSuitAsWord()
            ];
        }

        $data = [
            'cards' => $drawnCards,
            'cards left' => $deck->countCards()
        ];

        $session->set('deck', $deck);
        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
