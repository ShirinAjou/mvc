<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for handling lucky number and greeting routes.
 */
class LuckyController
{
    /**
     * Returns a response with a random lucky number.
     *
     * @return Response The rendered random lucky number page.
     */
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

    /**
     * Returns a response with a greeting message.
     *
     * @return Response The rendered greeting message page.
     */
    #[Route("/lucky/hi")]
    public function hiLucky(): Response
    {
        return new Response(
            '<html><body>Hi to you!</body></html>'
        );
    }
}
