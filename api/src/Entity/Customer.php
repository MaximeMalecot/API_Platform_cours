<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CustomerRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource]
#[Get(
    normalizationContext: [
        'groups' => [
            'Default', 'customer_get'
        ]
    ]
)]
#[GetCollection(
    normalizationContext: [
        'groups' => [
            'Default', 'customer_cget'
        ]
    ]
)]
#[Post(
    security: "is_granted('ROLE_DIRECTOR')",
    denormalizationContext: [
        'groups' => ['customer_write']
    ],
    normalizationContext: [
        'groups' => ['customer_get']
    ]
)]
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["customer_get", "customer_cget", "customer_write", "order_get"])]
    #[NotNull()]
    #[NotBlank()]
    #[Type('string')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["customer_get", "customer_cget", "customer_write", "order_get"])]
    #[NotNull()]
    #[NotBlank()]
    #[Type('string')]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["customer_get", "customer_write"])]
    #[NotNull()]
    #[NotBlank()]
    // #[Regex("/^((\+)33|0|0033)[1-9](\d{2}){4}$/i", message: "Wrong format")]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(["customer_get", "customer_write"])]
    #[NotNull()]
    #[NotBlank()]
    #[Type('string')]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Order::class, orphanRemoval: true)]
    #[Groups(["customer_get"])]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }
}
