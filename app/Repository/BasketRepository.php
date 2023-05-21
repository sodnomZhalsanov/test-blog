<?php

namespace App\Repository;
use App\Entity\Basket;
use PDO;
class BasketRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }

    public function getByUser(int $userId): Basket|null
    {
        $result = $this->connection->prepare("SELECT * FROM baskets WHERE user_id = ?");
        $result->execute([$userId]);

        $data = $result->fetch();

        if ($data) {
            $basket = new Basket($data['user_id']);
            $basket->setId($data['id']);

            return $basket;
        }

        return null;
    }


    public function save(Basket $basket): void
    {
        $result = $this->connection->prepare("INSERT INTO baskets (user_id) VALUES (:userId)");
        $result->execute(['userId' => $basket->getUserId()]);
    }

}