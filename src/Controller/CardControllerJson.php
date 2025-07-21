<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\CardHand;

class CardControllerJson
{
    // #[Route("/api")]
    // public function jsonNumber(): Response
    // {
    //     $data = [
    //         'quote' => '/api/quote',
    //         'show deck' => '/api/deck',
    //         'shuffle deck' => '/api/deck/shuffle',
    //         'draw card' => '/api/deck/draw',
    //         'draw card {number}' => '/api/deck/draw/{number}',
    //         'show current score' => '/api/game'
    //     ];

    //     $response = new JsonResponse($data);
    //     $response->setEncodingOptions(
    //         $response->getEncodingOptions() | JSON_PRETTY_PRINT
    //     );
    //     return $response;
    // }

    #[Route("/api/deck", name: "card_json")]
    public function deckJson(): Response
    {
        $deck = new DeckOfCards();
        $deck->sortCards();
        $cards = $deck->getCardsForJson();

        $response = new JsonResponse($cards);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "card_shuffle", methods: ["POST", "GET"])]
    public function shuffleDeck(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffleCards();
        $session->set('deck', $deck);
        $data = ['deck' => $deck->getCardsForJson()];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "card_draw", methods: ["POST", "GET"])]
    public function cardDraw(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $cardHand = new CardHand();
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

    #[Route("/api/deck/draw/{number}", name: "draw_cards", methods: ["POST", "GET"])]
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
