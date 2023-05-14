<?php

namespace App\Entity;

class Card
{
    private int $id;
    private string $name;
    private string $category;
    private float $price;

    public function __construct( $name, $price, $category)
    {

        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(int $name): void
    {
        $this->name = $name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }


}