<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Entity\Basket;
use App\Entity\BasketCard;
use App\Entity\Card;
use App\Repository\BasketCardRepository;
use App\Repository\BasketRepository;
use PDO;
use App\ViewRenderer;
use App\Service\BasketService;
use Throwable;

class BasketController
{

    private CardRepository $cardRepos;
    private BasketRepository $basketRepos;
    private BasketService $basketService;
    private ViewRenderer $renderer;

    private BasketCardRepository $basketCardRepos;

    private PDO $connection;

    public function __construct(
        CardRepository       $cardRepos,
        BasketRepository     $basketRepos,
        BasketService $basketService,
        ViewRenderer $renderer,
        BasketCardRepository $basketCardRepos,
        PDO                  $connection
    )
    {
        $this->cardRepos = $cardRepos;
        $this->basketRepos = $basketRepos;
        $this->basketService = $basketService;
        $this->renderer = $renderer;
        $this->basketCardRepos = $basketCardRepos;
        $this->connection = $connection;
    }

    public function getBasket(): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        $basketCards = $this->basketCardRepos->getByUser($_SESSION['userId']);


        return $this->renderer->render("../View/basket.phtml", [
            'basketCards' => $basketCards
        ]);
    }

    public function addToBasket(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        $cardId = $_POST['cardId'];
        $categoryId = $_POST['categoryId'];


        $errorMessage = $this->validate($cardId);

        if (empty($errorMessage)) {

            $userId = $_SESSION['userId'];

            $card = $this->cardRepos->getCardById($cardId);

            $this->basketService->AddCard($userId, $card);

            $basket = $this->basketService->getBasket($userId);

            header("Location: /catalog/$categoryId ");

        }

        header("Location: /catalog/$categoryId ");





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