<?php

namespace App\Entity;

use App\Api\Self\AsEndpoint;
use App\Api\Self\AsEndpointProperty;
use App\Repository\ShoppingCartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[AsEndpoint()]
#[ORM\Entity(repositoryClass: ShoppingCartItemRepository::class)]
class ShoppingCartItem extends Base
{
    #[AsEndpointProperty]
    #[ORM\ManyToOne(inversedBy: 'shoppingCartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShoppingCart $shoppingCart = null;

    #[AsEndpointProperty]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[AsEndpointProperty]
    #[ORM\Column]
    private ?int $quantity = null;

    #[AsEndpointProperty]
    #[ORM\ManyToOne(inversedBy: 'shoppingCartItems')]
    private ?Taxation $taxation = null;

    public function __construct()
    {
        $this->quantity = 0;
        parent::__construct();
    }

    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    public function setShoppingCart(?ShoppingCart $shoppingCart): static
    {
        $this->shoppingCart = $shoppingCart;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTaxation(): ?Taxation
    {
        return $this->taxation;
    }

    public function setTaxation(?Taxation $taxation): static
    {
        $this->taxation = $taxation;

        return $this;
    }
}
