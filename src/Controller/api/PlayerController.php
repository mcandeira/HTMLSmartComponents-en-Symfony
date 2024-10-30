<?php

namespace App\Controller\api;

use App\Entity\Player;
use App\Form\PlayerCreateType;
use App\Form\PlayerListType;
use App\Form\PlayerRegisterType;
use App\Form\PlayerRemoveType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PlayerController extends AbstractController
{
    #[Route('/api/jugador/crear', name: 'app_api_jugador_crear')]
    public function create(Request $request, EntityManagerInterface $EM): JsonResponse{

        $player = new Player();

        $form = $this->createForm(PlayerCreateType::class, $player);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $player = $form->getData();

            $EM->persist($player);
            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'Se ha creado un nuevo jugador',
                    'id' => $player->getId(),
                    'nombre' => $player->getName()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

    #[Route('/api/jugador/registrar', name: 'app_api_jugador_registrar')]
    public function registerr(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(PlayerRegisterType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $player = $registro['player'];
            $salary = $registro['salary'];

            if($player -> getClub()){
                return $this->json([
                    'resultado' => [
                        'mensaje' => 'El jugador ya está registrado en algún club',
                        'id' => $player->getID()
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

            $player->setSalary($salary);
            $club->addPlayer($player);
            $club->setBudget($budget - $salary);

            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'El jugador ha sido registrado exitosamente en en el club indicado',
                    'id_club' => $club->getID(),
                    'id_jugador' => $player->getID()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);

    }

    #[Route('/api/jugador/eliminar', name: 'app_api_jugador_eliminar')]
    public function remove(Request $request, EntityManagerInterface $EM): JsonResponse
    {
        $form = $this->createForm(PlayerRemoveType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $registro = $form->getData();

            $club = $registro['club'];
            $player = $registro['player'];

            if($player->getClub()->getID() != $club->getID()){
                return $this->json([
                    'resultado' => [
                        'mensaje' => 'El jugador no está registrado en dicho club',
                        'id_club' => $club->getID(),
                        'id_jugador' => $player->getID()
                    ]
                ]);
            }

            $club->setBudget($club->getBudget() + $player->getSalary());
            $player->setSalary(null);
            $club -> removePlayer($player);

            $EM->flush();

            return $this->json([
                'resultado' => [
                    'mensaje' => 'El jugador ha sido removido exitosamente del club indicado',
                    'id_club' => $club->getID(),
                    'id_jugador' => $player->getID()
                ]
            ]);
        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

    #[Route('/api/jugador/listar', name: 'app_api_jugador_listar')]
    public function list(Request $request, PlayerRepository $repo): JsonResponse
    {
        $form = $this->createForm(PlayerListType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            return $this->json([
                'paginas' => $repo->countPlayers($form->getData()['filter1'], $form->getData()['filter2']),
                'resultado' => $repo->listPlayers($form->getData()['page'], $form->getData()['filter1'], $form->getData()['filter2'])
            ]);

        }

        return $this->json([
            'error' => 'Los datos recibidos no son correctos',
            'resultado' => $form->all()
        ]);
    }

}