<?php

namespace App\Controller\api;

use App\Entity\Club;
use App\Form\ClubsListType;
use App\Form\ClubCreateType;
use App\Form\ClubModifyBudgetType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ClubController extends AbstractController
{
    #[Route('/api/club/crear', name: 'app_api_club_crear')]
    public function create(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $club = new Club();

        $form = $this->createForm(ClubCreateType::class, $club);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $club = $form->getData();

            $EM->persist($club);
            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'Se ha creado un nuevo club',
                    'id' => $club->getID(),
                    'nombre' => $club->getName(),
                    'presupuesto' => $club->getBudget()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

    #[Route('/api/club/modificar_presupuesto', name: 'app_api_modificar_presupuesto')]
    public function modifyBudget(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(ClubModifyBudgetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $club->setBudget($registro['budget']);

            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'Se ha actualizado el presupuesto del club',
                    'id' => $club->getID(),
                    'nombre' => $club->getName(),
                    'presupuesto' => $club->getBudget()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

    #[Route('/api/club/listar', name: 'app_api_club_listar')]
    public function list(Request $request, ClubRepository $repo): JsonResponse
    {
        $form = $this->createForm(ClubsListType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            return $this->json([
                'paginas' => $repo->countClubs($form->getData()['filter1'], $form->getData()['filter2']),
                'resultado' => $repo->listClubs($form->getData()['page'], $form->getData()['filter1'], $form->getData()['filter2'])
            ]);

        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

}