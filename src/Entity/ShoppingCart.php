<?php

namespace App\Entity;

use App\Api\Self\AsEndpoint;
use App\Api\Self\AsEndpointProperty;
use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[AsEndpoint()]
#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
class ShoppingCart extends Base
{
    #[AsEndpointProperty]
    #[ORM\OneToOne(inversedBy: 'shoppingCart')]
    private ?Customer $customer = null;

    #[ORM\OneToMany(mappedBy: 'shoppingCart', targetEntity: ShoppingCartItem::class, orphanRemoval: true)]
    private Collection $shoppingCartItems;

    public function __construct()
    {
        $this->shoppingCartItems = new ArrayCollection();
        parent::__construct();
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingCartItem>
     */
    public function getShoppingCartItems(): Collection
    {
        return $this->shoppingCartItems;
    }

    public function addShoppingCartItem(ShoppingCartItem $shoppingCartItem): static
    {
        if (!$this->shoppingCartItems->contains($shoppingCartItem)) {
            $this->shoppingCartItems->add($shoppingCartItem);
            $shoppingCartItem->setShoppingCart($this);
        }

        return $this;
    }

    public function removeShoppingCartItem(ShoppingCartItem $shoppingCartItem): static
    {
        if ($this->shoppingCartItems->removeElement($shoppingCartItem)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartItem->getShoppingCart() === $this) {
                $shoppingCartItem->setShoppingCart(null);
            }
        }

        return $this;
    }
}
