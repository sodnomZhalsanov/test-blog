<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\BasketCard;
use App\Entity\Card;
use App\Repository\BasketCardRepository;
use App\Repository\BasketRepository;
use PDO;

class BasketService
{
    private PDO $connection;
    private BasketRepository $basketRepository;

    private BasketCardRepository $basketCardRepository;

    public function __construct(
        PDO                  $connection,
        BasketRepository     $basketRepository,
        BasketCardRepository $basketCardRepository
    )
    {
        $this->connection = $connection;
        $this->basketRepository = $basketRepository;
        $this->basketCardRepository = $basketCardRepository;
    }

    public function getBasket(int $userId): Basket
    {
        $basket = $this->basketRepository->getByUser($userId);

        if (empty($basket)) {
            $basket = new Basket($userId);
            $this->basketRepository->save($basket);
        }

        return $basket;
    }

    public function AddCard(int $userId, Card $card): void
    {
        $this->connection->beginTransaction();

        try {
            $basket = $this->getBasket($userId);
            $cardId = $card->getId();
            $basketCard = $this->basketCardRepository->getOne($cardId, $userId);



            if (empty($basketCard)) {
                $basketCard = new BasketCard($card, $basket, 0);
            }

            $this->basketCardRepository->save($basketCard);
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }

        $this->connection->commit();

    }


}