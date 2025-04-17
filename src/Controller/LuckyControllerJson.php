<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("/api")]
    public function jsonNumber(): Response
    {
        $data = [
            'quote' => '/api/quote'
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        $number = random_int(0, 4); 
        if ($number === 1) {
            $quote = "Vad vi vet är inte vad vi lart, utan vad vi har kvar när vi glomt allt vi larde.";
        }

        elseif ($number === 2) {
            $quote = "Det ar inte en bugg, det ar en feature!";
        }

        else {
            $quote = "Den enda sanna matningen av programmerarens produktivitet ar antal svordomar per timme.";
        }

        $currentdate = date("F, j, Y");
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