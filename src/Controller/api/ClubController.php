<?php

namespace App\Controller\api;

use App\Entity\Club;
use App\Form\Type\ClubType;
use App\Form\Type\ListPlayersType;
use App\Form\Type\ModifyBudgetType;
use App\Form\Type\RegisterCoachType;
use App\Form\Type\RegisterPlayerType;
use App\Form\Type\RemoveCoachType;
use App\Form\Type\RemovePlayerType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ClubController extends AbstractController
{
    #[Route('/api/club', name: 'app_api_club')]
    public function new(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $club = new Club();

        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $club = $form->getData();

            $EM->persist($club);
            $EM->flush();

            return $this->json([
                'result' => 'Se ha creado un nuevo club',
                'clubID' => $club->getID(),
                'clubName' => $club->getName(),
                'clubBudget' => $club->getBudget(),
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);
    }

    #[Route('/api/club/register_player', name: 'app_api_club_register_player')]
    public function registerPlayer(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(RegisterPlayerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $player = $registro['player'];
            $salary = $registro['salary'];

            if($player -> getClub()){
                return $this->json([
                    'result' => 'El jugador ya está registrado en algún club',
                    'id' => $player->getID(),
                ]);
            }

            $budget = $club->getBudget();
            if($budget < $salary){
                return $this->json([
                    'result' => 'El presupuesto del club no es suficiente',
                    'Presupuesto' => $budget,
                    'Salario' => $salary,
                ]);
            }

            $player->setSalary($salary);
            $club->addPlayer($player);
            $club->setBudget($budget - $salary);

            $EM->flush();

            return $this->json([
                'result' => 'El jugador ha sido registrado exitosamente en en el club indicado',
                'clubID' => $club->getID(),
                'memberID' => $player->getID()
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);

    }

    #[Route('/api/club/register_coach', name: 'app_api_club_register_coach')]
    public function registerCoach(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(RegisterCoachType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $coach = $registro['coach'];
            $salary = $registro['salary'];

            if($coach -> getClub()){
                return $this->json([
                    'result' => 'El coach ya está registrado en algún club',
                    'id' => $coach->getID(),
                ]);
            }

            $budget = $club->getBudget();
            if($budget < $salary){
                return $this->json([
                    'result' => 'El presupuesto del club no es suficiente',
                    'Presupuesto' => $budget,
                    'Salario' => $salary,
                ]);
            }

            $coach->setSalary($salary);
            $club->addCoach($coach);
            $club->setBudget($budget - $salary);

            $EM->flush();

            return $this->json([
                'result' => 'El coach ha sido registrado exitosamente en en el club indicado',
                'clubID' => $club->getID(),
                'coachID' => $coach->getID()
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);

    }

    #[Route('/api/club/budget', name: 'app_api_club_budget')]
    public function modifyClubBudget(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(ModifyBudgetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $club->setBudget($registro['budget']);

            $EM->flush();

            return $this->json([
                'result' => 'Se ha actualizado el presupuesto del club',
                'clubID' => $club->getID(),
                'clubName' => $club->getName(),
                'clubBudget' => $club->getBudget(),
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);
    }

    #[Route('/api/club/remove_player', name: 'app_api_club_remove_player')]
    public function removePlayer(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(RemovePlayerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $player = $registro['player'];

            if($player->getClub()->getID() != $club->getID()){
                return $this->json([
                    'result' => 'El jugador no está registrado en dicho club',
                    'clubID' => $club->getID(),
                    'playerID' => $player->getID()
                ]);
            }

            $club->setBudget($club->getBudget() + $player->getSalary());
            $player->setSalary(null);
            $club -> removePlayer($player);

            $EM->flush();

            return $this->json([
                'result' => 'El jugador ha sido removido exitosamente del club indicado',
                'clubID' => $club->getID(),
                'playerID' => $player->getID()
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);
    }

    #[Route('/api/club/remove_coach', name: 'app_api_club_remove_coach')]
    public function removeCoach(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(RemoveCoachType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $coach = $registro['coach'];

            if($coach->getClub()->getID() != $club->getID()){
                return $this->json([
                    'result' => 'El coach no está registrado en dicho club',
                    'clubID' => $club->getID(),
                    'coachID' => $coach->getID()
                ]);
            }

            $club->setBudget($club->getBudget() + $coach->getSalary());
            $coach->setSalary(null);
            $club -> removeCoach($coach);

            $EM->flush();

            return $this->json([
                'result' => 'El coach ha sido removido exitosamente del club indicado',
                'clubID' => $club->getID(),
                'playerID' => $coach->getID()
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);
    }

    #[Route('/api/club/list_players', name: 'app_api_club_list_players')]
    public function listPlayers(Request $request, ClubRepository $repo): JsonResponse
    {
        $form = $this->createForm(ListPlayersType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $resultado = $repo -> listPlayers(
                $registro['club']->getID(),
                $registro['playerProperty'],
                $registro['condition'],
                $registro['referenceValue'],
                $registro['page']
            );

            return $this->json($resultado);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);
    }

}
