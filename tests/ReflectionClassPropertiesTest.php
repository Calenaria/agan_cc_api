<?php

namespace App\Tests;

use App\Api\Self\AsEndpointProperty;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReflectionClassPropertiesTest extends KernelTestCase
{
    /**
     * Tests with a Doctrine entity that has at least one (1) property tagged with the attribute 'AsEndpointProperty'
     */
    public function testReflectPropertiesFromClass(): void
    {
        /**
         * Setup to be able to use Symfony services (and custom ones)
         */
        self::bootKernel();
        $container = static::getContainer();
        
        $em = $container->get(EntityManagerInterface::class);

        /**
         * @var Customer $entity
         */
        $entity = $em->getRepository(Customer::class)->findOneBy(['id' => 1]);

        Assert::assertNotEmpty($entity);
        
        $reflectedEntity = new \ReflectionClass($entity);
        $reflectedEntityProperties = $reflectedEntity->getProperties();

        Assert::assertNotEmpty($reflectedEntityProperties);

        /**
         * @var ReflectionProperty $property
         */
        $taggedPropertyKeys = [];
        foreach($reflectedEntityProperties as $property) {
            if(!empty($property->getAttributes(AsEndpointProperty::class))) {
                $taggedPropertyKeys[] = $property->getName();
            }
        }
        
        $propertyArrayForJson = [];
        foreach($taggedPropertyKeys as $key) {
            $getter = 'get' . ucfirst($key);
            if (method_exists($entity, $getter)) {
                $propertyArrayForJson[$key] = $entity->$getter();
            }
        }

        Assert::isTrue(json_validate(json_encode($propertyArrayForJson)));
        
        return;
    }
}