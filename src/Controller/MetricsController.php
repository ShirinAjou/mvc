<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MetricsController extends AbstractController
{
    #[Route("/metrics", name: "metrics")]
    public function metrics(): Response
    {
        return $this->render('metrics/index.html.twig');
    }
}
