<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DetailRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource]
#[Get(
    normalizationContext: [
        'groups' => [
            'Default', 'detail_get'
        ]
    ]
)]
#[GetCollection(
    normalizationContext: [
        'groups' => [
            'Default', 'detail_cget'
        ]
    ]
)]
#[Post()]
#[ORM\Entity(repositoryClass: DetailRepository::class)]
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["detail_get", "detail_cget", "customer_get"])]
    private ?float $price = null;

    #[ORM\Column(length: 2)]
    #[Groups(["detail_get", "detail_cget", "customer_get"])]
    private ?string $size = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["detail_get", "customer_get"])]
    private ?Pizza $pizza = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["detail_get"])]
    private ?Order $order_delivery = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPizza(): ?Pizza
    {
        return $this->pizza;
    }

    public function setPizza(?Pizza $pizza): self
    {
        $this->pizza = $pizza;

        return $this;
    }

    public function getOrderDelivery(): ?Order
    {
        return $this->order_delivery;
    }

    public function setOrderDelivery(?Order $order_delivery): self
    {
        $this->order_delivery = $order_delivery;

        return $this;
    }
}
