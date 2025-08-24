<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling route related to metrics.
 */
class MetricsController extends AbstractController
{
    /**
     * Renders the metrics page.
     *
     * @return Response The rendered metrics page.
     */
    #[Route("/metrics", name: "metrics")]
    public function metrics(): Response
    {
        return $this->render('metrics/index.html.twig');
    }
}
