<?php

namespace App\Repository;
use PDO;
use App\Entity\Category;

class CategoryRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }

    public function getAllCategories(): array
    {
        $categories = [];
        $query = $this->connection->query("select * from categories");
        $result = $query->fetchAll();



        foreach ($result as $elem) {
            $category = new Category($elem['name']);
            $category->setId($elem['id']);

            $categories[] = $category;
        }



        return $categories;
    }

}