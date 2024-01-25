<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Api\Self\AsEndpointProperty;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\MappedSuperclass()]
#[HasLifecycleCallbacks]
class Base
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column]
    #[AsEndpointProperty]
    private ?int $id = null;

    #[AsEndpointProperty]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAtDate = null;

    #[AsEndpointProperty]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastModifiedDate = null;

    public function __construct()
    {
        $this->createdAtDate = new \DateTime();
        $this->lastModifiedDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAtDate(): ?\DateTimeInterface
    {
        return $this->createdAtDate;
    }

    public function setCreatedAtDate(\DateTimeInterface $createdAtDate): static
    {
        $this->createdAtDate = $createdAtDate;

        return $this;
    }

    public function getLastModifiedDate(): ?\DateTimeInterface
    {
        return $this->lastModifiedDate;
    }

    public function setLastModifiedDate(?\DateTimeInterface $lastModifiedDate): static
    {
        $this->lastModifiedDate = $lastModifiedDate;

        return $this;
    }

    #[ORM\PreUpdate]
    public function updateLastModifiedDate(): void
    {
        $this->lastModifiedDate = new \DateTime();
    }

    /**
     * To display all property types as JSON readable format, everything is normalized into an array to be used with json_encode()
     */
    public function normalize(bool $resolveReferences = false): array {
        $reflectedBase = new \ReflectionClass(Base::class);
        $reflectedBaseProperties = $reflectedBase->getProperties();

        $reflectedEntity = new \ReflectionClass($this);
        $reflectedEntityProperties = $reflectedEntity->getProperties();
    
        $allProperties = array_merge($reflectedBaseProperties, $reflectedEntityProperties);
    
        $propertyArrayForJson = [];
        foreach($allProperties as $property) {
            if(!empty($property->getAttributes(AsEndpointProperty::class))) {
                $getter = 'get' . ucfirst($property->getName());
                if (method_exists($this, $getter)) {
                    $value = $this->$getter();
                    
                    if ($value instanceof \DateTimeInterface) {
                        $value = $value->getTimestamp();
                    }
                    elseif ($value instanceof Base) {
                        if($resolveReferences) {
                            $value = $value->normalize($resolveReferences);
                            $propertyArrayForJson[$property->getName()] = $value;
                        } else {
                            $value = $value->getId();
                            $propertyArrayForJson[$property->getName()."Id"] = $value;
                        }
                        continue;
                    }
    
                    $propertyArrayForJson[$property->getName()] = $value;
                }
            }
        }
    
        return $propertyArrayForJson;
    }
}
