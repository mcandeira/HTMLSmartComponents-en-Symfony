<?php

namespace App\Controller\api;

use App\Entity\Coach;
use App\Form\CoachRegisterType;
use App\Form\CoachRemoveType;
use App\Form\CoachsListType;
use App\Form\CoachCreateType;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CoachController extends AbstractController
{
    #[Route('/api/entrenador/crear', name: 'app_api_coach_crear')]
    public function create(Request $request, EntityManagerInterface $EM): JsonResponse{

        $coach = new Coach();

        $form = $this->createForm(CoachCreateType::class, $coach);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $coach = $form->getData();

            $EM->persist($coach);
            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'Se ha creado un nuevo coach',
                    'id' => $coach->getId(),
                    'nombre' => $coach->getName()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

    #[Route('/api/entrenador/registrar', name: 'app_api_coach_registrar')]
    public function register(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(CoachRegisterType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $coach = $registro['coach'];
            $salary = $registro['salary'];

            if($coach -> getClub()){
                return $this->json([
                    'resultado' => [
                        'mensaje' => 'El coach ya está registrado en algún club',
                        'id' => $coach->getID()
                    ]
                ]);
            }

            $budget = $club->getBudget();
            if($budget < $salary){
                return $this->json([
                    'resultado' => [
                        'mensaje' => 'El presupuesto del club no es suficiente',
                        'presupuesto' => $budget,
                        'salario' => $salary
                    ]
                ]);
            }

            $coach->setSalary($salary);
            $club->addCoach($coach);
            $club->setBudget($budget - $salary);

            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'El coach ha sido registrado exitosamente en en el club indicado',
                    'id_club' => $club->getID(),
                    'id_coach' => $coach->getID()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);

    }

    #[Route('/api/entrenador/eliminar', name: 'app_api_coach_eliminar')]
    public function remove(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(CoachRemoveType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $coach = $registro['coach'];

            if($coach->getClub()->getID() != $club->getID()){
                return $this->json([
                    'resultado' => [
                        'mensaje' => 'El coach no está registrado en dicho club',
                        'id_club' => $club->getID(),
                        'id_coach' => $coach->getID()
                    ]
                ]);
            }

            $club->setBudget($club->getBudget() + $coach->getSalary());
            $coach->setSalary(null);
            $club -> removeCoach($coach);

            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'El coach ha sido removido exitosamente del club indicado',
                    'id_club' => $club->getID(),
                    'id_player' => $coach->getID()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all(),
        ]);
    }

    #[Route('/api/entrenador/listar', name: 'app_api_coach_listar')]
    public function list(Request $request, CoachRepository $repo): JsonResponse
    {
        $form = $this->createForm(CoachsListType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            return $this->json([
                'paginas' => $repo->countCoaches($form->getData()['filter1'], $form->getData()['filter2']),
                'resultado' => $repo->listCoaches($form->getData()['page'], $form->getData()['filter1'], $form->getData()['filter2'])
            ]);

        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

}