<?php

namespace App\Controller;
use App\Repository\CardRepository;
use App\Entity\Basket;
use App\Entity\BasketCard;
use App\Entity\Card;
use App\Repository\BasketCardRepository;
use App\Repository\BasketRepository;
use PDO;


class BasketController
{

    private CardRepository $cardRepos;
    private BasketRepository $basketRepos;

    private BasketCardRepository $basketCardRepos;

    private PDO $connection;

    public function __construct(
        CardRepository       $cardRepos,
        BasketRepository     $basketRepos,
        BasketCardRepository $basketCardRepos,
        PDO                  $connection
    )
    {
        $this->cardRepos = $cardRepos;
        $this->basketRepos = $basketRepos;
        $this->basketCardRepos = $basketCardRepos;
        $this->connection = $connection;
    }

    public function getBasket(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        $cards = $this->cardRepos->getByUser($_SESSION['userId']);


        return [
            "../View/basket.phtml",
            [

                'cards' => $cards,
            ]
        ];
    }

    public function AddToBasket(Card $card): void
    {
        $categoryId = $_POST['categoryId'];
        $cardId = $_POST['cardId'];

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errorMessage = $this->validate($categoryId);

            if (empty($errorMessage)) {

                $userId = $_SESSION['userId'];
                $basketCard = $this->basketCardRepos->getOne($cardId, $userId);
                $basket = $this->basketRepos->getByUser($userId);

                $this->connection->beginTransaction();

                try {
                    if (empty($basket)) {
                        $basket = new Basket($userId);
                        $this->basketRepos->save($basket);
                    }

                    if (empty($basketCard)) {
                        $card = $this->cardRepos->getCardById($cardId);
                        $basketCard = new BasketCard($card, $basket, 0);
                    }

                    $this->basketCardRepos->save($basketCard);

                } catch (\Throwable) {
                    $this->connection->rollBack();
                }

                $this->connection->commit();

                header("Location: /category/$categoryId");

            }


        }


    }

    public function validate(int $cardId): array
    {
        $errors = [];

        if (empty($cardId)) {
            $errors['cardId'] = 'Invalid card ID';
        }

        return $errors;
    }
}