<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/tasks')]
class TaskRestController extends AbstractController
{
    #[Route('', name: 'api_task_index', methods: ['GET'])]
    public function index(Request $request, TaskRepository $taskRepository): JsonResponse
    {
        $filter = $request->query->get('filter', 'all');
        
        $tasks = match ($filter) {
            'completed' => $taskRepository->findBy(['status' => true]),
            'pending' => $taskRepository->findBy(['status' => false]),
            default => $taskRepository->findAll(),
        };

        return $this->json([
            'status' => 'success',
            'data' => $tasks,
            'filter' => $filter
        ], Response::HTTP_OK, [], ['groups' => 'task:read']);
    }

    #[Route('/{id}', name: 'api_task_show', methods: ['GET'])]
    public function show(Task $task): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'data' => $task
        ], Response::HTTP_OK, [], ['groups' => 'task:read']);
    }

    #[Route('', name: 'api_task_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $task = $serializer->deserialize($request->getContent(), Task::class, 'json');
            
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Tâche créée avec succès',
                'data' => $task
            ], Response::HTTP_CREATED, [], ['groups' => 'task:read']);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'api_task_update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, Task $task, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        try {
            $serializer->deserialize(
                $request->getContent(), 
                Task::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $task]
            );
            
            $entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Tâche mise à jour avec succès',
                'data' => $task
            ], Response::HTTP_OK, [], ['groups' => 'task:read']);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/toggle', name: 'api_task_toggle', methods: ['PATCH'])]
    public function toggle(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        $task->setStatus(!$task->getStatus());
        $entityManager->flush();
        
        return $this->json([
            'status' => 'success',
            'message' => 'Statut de la tâche mis à jour avec succès',
            'data' => $task
        ], Response::HTTP_OK, [], ['groups' => 'task:read']);
    }

    #[Route('/{id}', name: 'api_task_delete', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($task);
        $entityManager->flush();
        
        return $this->json([
            'status' => 'success',
            'message' => 'Tâche supprimée avec succès'
        ], Response::HTTP_OK);
    }
}