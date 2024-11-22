<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;

#[Cache(public: true, maxage: 300, staleWhileRevalidate: 300)]
class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/{element<jugador|entrenador|club>}/{action<crear|registrar|eliminar|listar|modificar_presupuesto>}', name: 'app_actions')]
    public function actions(string $element, string $action): Response
    {
        return $this->render('actions.html.twig', [
            'element' => $element,
            'action' => $action
        ]);
    }

    #[Route('/examples/{ejemplo<.*>}', name: 'app_examples')]
    public function examples(string $ejemplo): Response
    {
        return $this->render('examples.html.twig', ['ejemplo' => $ejemplo]);
    }

}