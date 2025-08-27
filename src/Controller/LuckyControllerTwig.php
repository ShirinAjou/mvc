<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling routes that render Twig templates.
 */
class LuckyControllerTwig extends AbstractController
{
    /**
     * Renders the home page.
     *
     * @return Response The rendered home page.
     */
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * Renders the about page.
     *
     * @return Response The rendered about page.
     */
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    /**
     * Renders the report page.
     *
     * @return Response The rendered report page.
     */
    #[Route('/report', name: 'report')]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    /**
     * Renders the lucky number page with a random number.
     *
     * @return Response The rendered lucky number page with a random number.
     */
    #[Route('/lucky', name: 'lucky_number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
}
