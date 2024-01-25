<?php

namespace App\Entity;

use App\Api\Self\AsEndpoint;
use App\Api\Self\AsEndpointProperty;
use App\Repository\TaxationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[AsEndpoint()]
#[ORM\Entity(repositoryClass: TaxationRepository::class)]
class Taxation extends Base
{
    #[AsEndpointProperty]
    #[ORM\Column(length: 255)]
    private ?string $taxName = null;
    
    #[AsEndpointProperty]
    #[ORM\Column]
    private ?float $taxValuePercentage = null;

    #[ORM\OneToMany(mappedBy: 'taxation', targetEntity: ShoppingCartItem::class)]
    private Collection $shoppingCartItems;

    public function __construct()
    {
        parent::__construct();
        $this->shoppingCartItems = new ArrayCollection();
    }

    public function getTaxName(): ?string
    {
        return $this->taxName;
    }

    public function setTaxName(string $taxName): static
    {
        $this->taxName = $taxName;

        return $this;
    }

    public function getTaxValuePercentage(): ?float
    {
        return $this->taxValuePercentage;
    }

    public function setTaxValuePercentage(float $taxValuePercentage): static
    {
        $this->taxValuePercentage = $taxValuePercentage;

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
            $shoppingCartItem->setTaxation($this);
        }

        return $this;
    }

    public function removeShoppingCartItem(ShoppingCartItem $shoppingCartItem): static
    {
        if ($this->shoppingCartItems->removeElement($shoppingCartItem)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartItem->getTaxation() === $this) {
                $shoppingCartItem->setTaxation(null);
            }
        }

        return $this;
    }
}
