<?php

namespace App\Entity;

class Card
{
    private int $id;
    private string $name;
    private int $categoryId;
    private float $price;

    private ?string $image;

    public function __construct(string $name,string $price,int $categoryId,?string $image)
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


    public function getCategory(): int
    {
        return $this->categoryId;
    }



    public function getPrice(): float
    {
        return $this->price;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }


}