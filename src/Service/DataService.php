<?php

namespace App\Service;

use App\Api\Self\AsEndpointProperty;
use App\Entity\Base;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DataService {

    public function __construct(private EntityManagerInterface $em) {}

    public function overwriteEntityWithJsonData(object $entity, array $jsonData): object {
        $reflectedEntity = new \ReflectionClass($entity);
        $reflectedEntityProperties = $reflectedEntity->getProperties();
    
        foreach($reflectedEntityProperties as $property) {
            if(!empty($property->getAttributes(AsEndpointProperty::class))) {
                $setter = 'set' . ucfirst($property->getName());
                if (method_exists($entity, $setter)) {
                    
                    $ormAttributes = $property->getAttributes(Column::class);
                    if (!empty($ormAttributes)) {
                        $ormAttribute = $ormAttributes[0]->newInstance();
                        if (!isset($ormAttribute->nullable) || !$ormAttribute->nullable) {
                            if (!isset($jsonData[$property->getName()]) && !isset($jsonData[$property->getName() . 'Id'])) {
                                throw new \InvalidArgumentException("Missing required property {$property->getName()}");
                            }
                        }
                    }
                    
                    $jsonValue = $jsonData[$property->getName()] ?? $jsonData[$property->getName() . 'Id'] ?? null;
                    if ($jsonValue !== null) {
                        if ($property->getType() && is_subclass_of($property->getType()->getName(), Base::class)) {
                            $repository = $this->em->getRepository($property->getType()->getName());
                            $value = $repository->find($jsonValue);
                            if (!$value) {
                                throw new NotFoundHttpException("Entity with id {$jsonValue} not found");
                            }
                        } else {
                            $value = $jsonValue;
                        }
                        $entity->$setter($value);
                    }
                }
            }
        }
        return $entity;
    }
}

