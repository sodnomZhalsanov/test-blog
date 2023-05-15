<?php

namespace App\Entity;

class Category
{
    private int $id;
    private string $name;
    public function __construct($name)
    {
        $this->name = $name;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }

}