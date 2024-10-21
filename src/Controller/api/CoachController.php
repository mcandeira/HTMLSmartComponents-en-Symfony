<?php

namespace App\Controller\api;

use App\Entity\Coach;
use App\Form\Type\CoachType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CoachController extends AbstractController
{
    #[Route('/api/coach', name: 'app_api_coach')]
    public function new(Request $request, EntityManagerInterface $EM): JsonResponse{

        $coach = new Coach();

        $form = $this->createForm(CoachType::class, $coach);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $coach = $form->getData();

            $EM->persist($coach);
            $EM->flush();

            return $this->json([
                'result' => 'Se ha creado un nuevo coach',
                'id' => $coach->getId(),
                'name' => $coach->getName(),
            ]);
        }

        return $this->json([
            'result' => 'Los datos recibidos no son correctos',
            'datos_recibidos' => $form->all()
        ]);
    }

}