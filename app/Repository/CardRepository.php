<?php

namespace App\Repository;

use PDO;
use App\Entity\Card;

class CardRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }

    public function getCardById(array $data): Card|bool
    {
        $query = $this->connection->prepare("select * from cards where id = :id");
        $query->execute(['id' => $data['id']]);
        $result = $query->fetch();

        if (!empty($result)) {
            $card = new Card(
                $result['name'],
                $result['category'],
                $result['price']
            );
            $card->setId($data['id']);

            return $card;
        }
        return false;
    }

    public function getAllCards(): ?array
    {
        $query = $this->connection->prepare("select * from cards");
        $query->execute();
        $result = $query->fetchAll();

        return $result;
    }

}