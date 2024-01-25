<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Api\Self\AsEndpoint;
use App\Entity\Helper\CriteriaHelper;
use App\Service\DataService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *  Dynamic RESTful API controller
 */
#[Route('/api/v1')]
class ApiController extends AbstractController
{
    public const ENTITY_BASE_NAMESPACE = 'App\\Entity\\';

    function __construct(public LoggerInterface $logger, public EntityManagerInterface $em, private ValidatorInterface $validator, private DataService $dataService) {}

    #[Route("/{entity}/{id}", 'get_record_by_id', methods: ['GET'])]
    public function retrieveRecordById($entity, $id, Request $request): Response {
        $entityClass = $this->getEntityClass($entity);
    
        if(empty($record = $this->em->find($entityClass, $id))) {
            return new JsonResponse([
                'error' => "$id not found"
            ], 404);
        }
        return new JsonResponse($record->normalize(str_contains($request->getQueryString(), "resolveReferences")));
    }
    
    #[Route("/{entity}s", 'collection_get_records', methods: ["GET"])]
    public function retrieveRecord($entity, Request $request): Response {
        $entityClass = $this->getEntityClass($entity);
    
        $collectedRecords = $this->em->getRepository($entityClass)->matching(CriteriaHelper::createCriteriaFromQueryParameter($request->getQueryString() ?? "", $entityClass));
    
        $normalizedEntities = [];
        foreach($collectedRecords as $record) {
            $normalizedEntities[] = $record->normalize(str_contains($request->getQueryString(), "resolveReferences"));
        }
    
        return $this->json($normalizedEntities);
    }

    #[Route("/{entity}s", 'collection_post_record', methods: ["POST"])]
    public function createRecord($entity, Request $request): Response {
        $entityClass = $this->getEntityClass($entity);
    
        $data = json_decode($request->getContent(), true);
    
        $newEntity = new $entityClass();
        try {
            $newEntity = $this->dataService->overwriteEntityWithJsonData($newEntity, $data);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    
        try {
            $this->em->persist($newEntity);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage() . ' at line ' . $e->getLine() . ' in ' . $e->getFile());
            return new JsonResponse(['error' => 'Something went wrong!'], 500);
        }
        
        return $this->json($newEntity->normalize(), Response::HTTP_CREATED);
    }
    
    #[Route("/{entity}/{id}", 'delete_record_by_id', methods: ['DELETE'])]
    public function deleteRecordById($entity, $id): Response {
        $entityClass = $this->getEntityClass($entity);
    
        $record = $this->em->find($entityClass, $id);
    
        if (!$record) {
            return new JsonResponse([
                'error' => "$id not found"
            ], 404);
        }
    
        try {
            $this->em->persist($record);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage() . ' at line ' . $e->getLine() . ' in ' . $e->getFile());
            return new JsonResponse(['error' => 'Something went wrong!'], 500);
        }
    
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    
    #[Route("/{entity}/{id}", 'update_record_by_id', methods: ['PUT'])]
    public function updateRecordById($entity, $id, Request $request): Response {
        $entityClass = $this->getEntityClass($entity);
    
        $record = $this->em->find($entityClass, $id);
    
        if (!$record) {
            return new JsonResponse([
                'error' => "$id not found"
            ], 404);
        }
    
        $data = json_decode($request->getContent(), true);
    
        try {
            $record = $this->dataService->overwriteEntityWithJsonData($record, $data);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    
        $this->em->flush();
    
        return $this->json($record->normalize());
    }

    private function getEntityClass($entity): string {
        $entityClass = self::ENTITY_BASE_NAMESPACE . ucfirst($entity);
    
        if (!class_exists($entityClass)) {
            $this->logger->debug("Class for $entity doesn't exist");
            throw new NotFoundHttpException('Endpoint not available');
        }
    
        $reflection = new \ReflectionClass($entityClass);
    
        if(!$reflection->getAttributes(AsEndpoint::class)) {
            $this->logger->debug("Entity does exist, but has no 'AsEndpoint' attribute");
            throw new NotFoundHttpException('Endpoint not available');
        }
    
        return $entityClass;
    }
}