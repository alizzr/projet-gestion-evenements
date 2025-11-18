<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/events')]
class EventController extends AbstractController
{
    // LISTER
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $events = $em->getRepository(Event::class)->findAll();
        $data = [];
        foreach ($events as $event) {
            $data[] = ['id' => $event->getId(), 'name' => $event->getName(), 'date' => $event->getDate()];
        }
        return $this->json($data);
    }

    // CRÉER
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $event = new Event();
        $event->setName($data['name'] ?? 'Événement');
        $event->setDate($data['date'] ?? date('Y-m-d'));
        $em->persist($event);
        $em->flush();
        return $this->json(['status' => 'Créé', 'id' => $event->getId()], 201);
    }

    // SUPPRIMER
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $event = $em->getRepository(Event::class)->find($id);
        if ($event) {
            $em->remove($event);
            $em->flush();
        }
        return $this->json(['status' => 'Supprimé']);
    }
}