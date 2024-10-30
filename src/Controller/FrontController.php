<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/{element}/{action}', name: 'app_actions')]
    public function actions(string $element, string $action): Response
    {
        return $this->render('actions.html.twig', [
            'element' => $element,
            'action' => $action
        ]);
    }

}