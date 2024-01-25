<?php

namespace App\Entity;

use App\Api\Self\AsEndpoint;
use App\Api\Self\AsEndpointProperty;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[AsEndpoint]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article extends Base
{
    #[AsEndpointProperty]
    #[ORM\Column(length: 255)]
    private ?string $articleNumber = null;

    #[AsEndpointProperty]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[AsEndpointProperty]
    #[ORM\Column]
    private ?int $basePrice = null;

    public function getArticleNumber(): ?string
    {
        return $this->articleNumber;
    }

    public function setArticleNumber(string $articleNumber): static
    {
        $this->articleNumber = $articleNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBasePrice(): ?int
    {
        return $this->basePrice;
    }

    public function setBasePrice(int $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }
}
