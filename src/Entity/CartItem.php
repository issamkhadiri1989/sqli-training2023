<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CartItemRepository;
use App\Validator\Constraint\InStore;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[InStore(groups: ['add_to_cart', 'cart'])]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull(groups: ['add_to_cart', 'cart']), Assert\NotBlank(groups: ['add_to_cart', 'cart']), Assert\GreaterThan(0, groups: ['add_to_cart', 'cart'])]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Computes and get the total amount of the cart item.
     *
     * @return float
     */
    public function computeTotal(): float
    {
        return $this->quantity * $this->getProduct()->getUnitPrice();
    }

    public function updateQuantity(int $quantity): self
    {
        $this->quantity += $quantity;

        return $this;
    }
}
