<?php

namespace App\Controller\api;

use App\Entity\Player;
use App\Form\Type\PlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PlayerController extends AbstractController
{
    #[Route('/api/player', name: 'app_api_player')]
    public function new(Request $request, EntityManagerInterface $EM): JsonResponse{

        $player = new Player();

        $form = $this->createForm(PlayerType::class, $player);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $player = $form->getData();

            $EM->persist($player);
            $EM->flush();

            return $this->json([
                'result' => 'Se ha creado un nuevo jugador',
                'id' => $player->getId(),
                'name' => $player->getName(),
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);
    }

}