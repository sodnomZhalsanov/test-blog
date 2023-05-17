<?php

namespace App\Entity;

class Card
{
    private int $id;
    private string $name;
    private string $categoryId;
    private float $price;

    private ?string $image;

    public function __construct( $name, $price, $categoryId,$image)
    {

        $this->name = $name;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->image = $image;
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
        return $this->category_id;
    }

    public function setCategory(int $category_id): void
    {
        $this->category_id = $category_id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }


}