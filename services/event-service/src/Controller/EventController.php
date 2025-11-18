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
    // 1. LISTER (GET)
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $events = $em->getRepository(Event::class)->findAll();
        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'date' => $event->getDate(),
            ];
        }
        return $this->json($data);
    }

    // 2. CRÉER (POST)
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $event = new Event();
        $event->setName($data['name']);
        $event->setDate($data['date']);

        $em->persist($event);
        $em->flush();

        return $this->json(['status' => 'Event created', 'id' => $event->getId()], 201);
    }

    // 3. MODIFIER (PUT)
    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) return $this->json(['error' => 'Not found'], 404);

        $data = json_decode($request->getContent(), true);
        
        if(isset($data['name'])) $event->setName($data['name']);
        if(isset($data['date'])) $event->setDate($data['date']);

        $em->flush();

        return $this->json(['status' => 'Event updated']);
    }

    // 4. SUPPRIMER (DELETE)
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $event = $em->getRepository(Event::class)->find($id);
        if (!$event) return $this->json(['error' => 'Not found'], 404);

        $em->remove($event);
        $em->flush();

        return $this->json(['status' => 'Event deleted']);
    }
}