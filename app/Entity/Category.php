<?php

namespace App\Entity;

class Category
{
    private int $id;
    private string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

}