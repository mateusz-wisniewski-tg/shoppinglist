<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ShoppingListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingListRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ShoppingList
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 500)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $fulfilled = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(targetEntity: ShoppingListItem::class, mappedBy: 'shoppingList', orphanRemoval: true)]
    private Collection $shoppingListItems;

    #[ORM\OneToMany(targetEntity: Sharee::class, mappedBy: 'shoppingList', orphanRemoval: true)]
    private Collection $sharees;

    public function __construct()
    {
        $this->shoppingListItems = new ArrayCollection();
        $this->sharees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isFulfilled(): ?bool
    {
        return $this->fulfilled;
    }

    public function setFulfilled(bool $fulfilled): static
    {
        $this->fulfilled = $fulfilled;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingListItem>
     */
    public function getShoppingListItems(): Collection
    {
        return $this->shoppingListItems;
    }

    public function addShoppingListItem(ShoppingListItem $shoppingListItem): static
    {
        if (!$this->shoppingListItems->contains($shoppingListItem)) {
            $this->shoppingListItems->add($shoppingListItem);
            $shoppingListItem->setShoppingList($this);
        }

        return $this;
    }

    public function removeShoppingListItem(ShoppingListItem $shoppingListItem): static
    {
        if ($this->shoppingListItems->removeElement($shoppingListItem)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListItem->getShoppingList() === $this) {
                $shoppingListItem->setShoppingList(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sharee>
     */
    public function getSharees(): Collection
    {
        return $this->sharees;
    }

    public function addSharee(Sharee $sharee): static
    {
        if (!$this->sharees->contains($sharee)) {
            $this->sharees->add($sharee);
            $sharee->setShoppingList($this);
        }

        return $this;
    }

    public function removeSharee(Sharee $sharee): static
    {
        if ($this->sharees->removeElement($sharee)) {
            // set the owning side to null (unless already changed)
            if ($sharee->getShoppingList() === $this) {
                $sharee->setShoppingList(null);
            }
        }

        return $this;
    }
}
