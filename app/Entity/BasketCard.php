<?php

namespace App\Entity;

class BasketCard
{
    private Card $card;
    private Basket $basket;

    private int $quantity;

    public function __construct(Card $card, Basket $basket, int $quantity)
    {
        $this->card = $card;
        $this->basket = $basket;
        $this->quantity = $quantity;
    }

    public function getCard(): Card
    {
        return $this->card;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

}