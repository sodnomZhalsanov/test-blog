<?php

namespace App\Entity;

class Basket
{
    private int $id;

    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getUserId(): int
    {
        return $this->userId;
    }

}