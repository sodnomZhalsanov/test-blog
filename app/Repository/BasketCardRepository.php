<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\Card;
use App\Entity\BasketCard;
use PDO;

class BasketCardRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }

    public function getOne(int $cardId, int $userId): BasketCard|null
    {
        $query = $this->connection->prepare(
            "select * from basket_cards b_c
                   inner join baskets b on b_c.basket_id = b.id
                   inner join cards c on b_c.card_id = c.id
                   inner join users u on b.user_id = u.id
                   where c.id = :cardId and u.id = :userId"
        );
        $query->execute([
            'cardId' => $cardId,
            'userId' => $userId
        ]);
        $data = $query->fetch();

        if($data) {
            $card = new Card(
                $data['name'],
                $data['price'],
                $data['category_id'],
                $data['image']
            );

            $card->setId($data['id']);

            $basket = new Basket($data['user_id']);

            $basket->setId($data['basket_id']);

            return New BasketCard($card, $basket, $data['quantity']);
        }
        return null;
    }

    public function getByUser(int $userId): array
    {
        $result = $this->connection->prepare(
            "SELECT * FROM basket_cards b_c
                    INNER JOIN cards c on b_c.card_id = c.id
                    INNER JOIN baskets b on b_c.basket_id = b.id
                    INNER JOIN users u on b.user_id = u.id
                    WHERE u.id = ?"
        );
        $result->execute([$userId]);
        $data = $result->fetchAll();

        $cards= [];

        if ($data) {
            foreach ($data as $elem) {
                $card = new Card(
                    $elem['name'],
                    $elem['price'],
                    $elem['category_id'],
                    $elem['image']
                );

                $card->setId($elem['id']);

                $basket = new Basket($elem['user_id']);

                $basket->setId($elem['basket_id']);

                $basketCard = new BasketCard($card, $basket, $elem['quantity']);

                $cards[] = $basketCard;
            }
        }

        return $cards;
    }


    public function save(BasketCard $basketCard): void
    {
        $result = $this->connection->prepare("
                   INSERT INTO basket_cards (
                           card_id, 
                           basket_id, 
                           quantity
                   ) VALUES (
                           :cardId,
                           :basketId,
                           :quantity 
                   ) ON CONFLICT (card_id, basket_id) DO UPDATE 
                   SET quantity = EXCLUDED.quantity
        ");

        $result->execute([
            'cardId' => $basketCard->getCard()->getId(),
            'basketId' => $basketCard->getBasket()->getId(),
            'quantity' => $basketCard->getQuantity() + 1,
        ]);

    }


    public function getQuantityByBasket(int $basketId): int
    {
        $result = $this->connection->prepare(
            "SELECT SUM(quantity) as summ FROM basket_cards WHERE basket_id = ? GROUP BY basket_id"
        );
        $result->execute([$basketId]);

        $data = $result->fetch();

        if ($data){
            return $data['summ'];
        }

        return 0;
    }
}








