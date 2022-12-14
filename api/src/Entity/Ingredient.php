<?php

namespace App\Entity;

use App\Entity\Pizza;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

#[ApiResource(
    normalizationContext: [
        'groups' => ['ingredient_read']
    ]
)]
#[Get]
#[GetCollection]
#[Post(
    security: "is_granted('ROLE_DIRECTOR')",
    denormalizationContext: [
        'groups' => ['ingredient_write']
    ],
    normalizationContext: [
        'groups' => ['ingredient_read']
    ]
)]
#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[UniqueEntity('name', message: "Already taken")]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    #[Groups(["pizza_get","ingredient_read", "ingredient_write"])]
    #[NotNull()]
    #[NotBlank()]
    #[Type('string')]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Pizza::class, inversedBy: 'ingredients')]
    #[Groups("ingredient_read")]
    private Collection $pizzas;

    public function __construct()
    {
        $this->pizzas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Pizza>
     */
    public function getPizzas(): Collection
    {
        return $this->pizzas;
    }

    public function addPizza(Pizza $pizza): self
    {
        if (!$this->pizzas->contains($pizza)) {
            $this->pizzas[] = $pizza;
        }

        return $this;
    }

    public function removePizza(Pizza $pizza): self
    {
        $this->pizzas->removeElement($pizza);

        return $this;
    }
}
