<?php

namespace App\Repository;

use PDO;
use App\Entity\Card;
use App\Entity\Category;

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
                $result['price'],
                $result['image']
            );
            $card->setId($data['id']);

            return $card;
        }
        return false;
    }

    public function getAllCards(): ?array
    {
        $cards = [];
        $query = $this->connection->query("select * from cards");
        $result = $query->fetchAll();


        foreach ($result as $elem) {
            $card = new Card($elem['name'], $elem['price'], $elem['category'], $elem['image']);
            $card->setId($elem['id']);

            $cards[] = $card;
        }


        return $cards;
    }

    public function getByCategory(int $id)
    {

    }
}
