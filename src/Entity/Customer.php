<?php

namespace App\Entity;

use App\Api\Self\AsEndpoint;
use App\Api\Self\AsEndpointProperty;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

#[AsEndpoint(overrideEndpointDesignation: 'kunde')]
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer extends Base
{
    #[AsEndpointProperty]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[AsEndpointProperty]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\OneToOne(mappedBy: 'customer', cascade: ['persist', 'remove'])]
    private ?ShoppingCart $shoppingCart = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    public function setShoppingCart(?ShoppingCart $shoppingCart): static
    {
        // unset the owning side of the relation if necessary
        if ($shoppingCart === null && $this->shoppingCart !== null) {
            $this->shoppingCart->setCustomer(null);
        }

        // set the owning side of the relation if necessary
        if ($shoppingCart !== null && $shoppingCart->getCustomer() !== $this) {
            $shoppingCart->setCustomer($this);
        }

        $this->shoppingCart = $shoppingCart;

        return $this;
    }
}
