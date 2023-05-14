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

    public function getCardByName($name): Card|bool
    {
        $query = $this->connection->prepare("select * from cards where name = :name");
        $query->execute(['name' => $name]);
        $result = $query->fetch();

        if (!empty($result)) {
            $card = new Card(
                $result['id'],
                $result['name'],
                $result['category'],
                $result['price']
            );

            return $card;
        }
        return false;
    }

}