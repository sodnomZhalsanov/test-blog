<?php

namespace App\Repository;

use PDO;
use App\Entity\Card;
use App\Entity\Category;
use App\Entity\Basket;

class CardRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }

    public function getCardById(int $cardId): Card|null
    {
        $query = $this->connection->prepare("select * from cards where id = :id");
        $query->execute(['id' => $cardId]);
        $result = $query->fetch();

        if (!empty($result)) {
            $card = new Card(
                $result['name'],
                $result['category_id'],
                $result['price'],
                $result['image']
            );
            $card->setId($result['id']);

            return $card;
        }
        return null;
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

    public function getByCategory(int $id): array
    {
        $cards = [];
        $query = $this->connection->prepare("select * from cards where category_id = :id");
        $query->execute(['id' => $id]);
        $result = $query->fetchAll();

        foreach ($result as $elem) {
            $card = new Card($elem['name'], $elem['price'], $elem['category_id'], $elem['image']);
            $card->setId($elem['id']);

            $cards[] = $card;
        }

        return $cards;

    }

    public function getByUser(int $userId): array
    {
        $cards = [];
        $query = $this->connection->prepare(
            "SELECT * FROM
             cards c inner join basket_cards b_c on c.id = b_c.card_id
             inner join  baskets b on b_c.basket_id = b.id
             WHERE b.user_id = ?"
        );
        $query->execute([$userId]);
        $result = $query->fetchAll();

        foreach ($result as $elem) {
            $card = new Card($elem['name'], $elem['price'], $elem['category_id'], $elem['image']);
            $card->setId($elem['id']);

            $cards[] = $card;

        }

        return $cards;



    }
}
