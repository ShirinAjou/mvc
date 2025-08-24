<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling JSON responses related to lucky numbers and greeting routes.
 */
class LuckyControllerJson
{
    /**
     * Returns a JSON response with all available api.
     *
     * @return Response A JSON response listing api.
     */
    #[Route("/api")]
    public function jsonNumber(): Response
    {
        $data = [
            'quote' => '/api/quote',
            'show deck' => '/api/deck',
            'shuffle deck' => '/api/deck/shuffle',
            'draw card' => '/api/deck/draw',
            'draw card {number}' => '/api/deck/draw/{number}',
            'show current score' => '/api/game',
            'show books' => '/api/library/books',
            'show book {isbn}' => '/api/library/book/{isbn}'
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Returns a JSON response with a random quote and the current date and time.
     *
     * @return Response A JSON response with a random quote, the current date and time.
     */
    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        $number = random_int(0, 4);

        if ($number === 1) {
            $quote = "Vad vi vet är inte vad vi lart, utan vad vi har kvar när vi glomt allt vi larde.";
        }
        if ($number === 2) {
            $quote = "Det ar inte en bugg, det ar en feature!";
        }
        if (!isset($quote)) {
            $quote = "Den enda sanna matningen av programmerarens produktivitet ar antal svordomar per timme.";
        }

        $currentdate = date("F j Y");
        $currenttime = date("H:i:s");

        $data = [
            'quote' => $quote,
            'date' => $currentdate,
            'time' => $currenttime
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
