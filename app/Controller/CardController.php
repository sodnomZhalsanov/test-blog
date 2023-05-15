<?php

namespace App\Controller;


use App\Repository\CardRepository;
use App\Entity\Card;


class CardController
{
    private CardRepository $cardRepos;

    public function __construct(CardRepository $cardRepos)
    {
        $this->cardRepos = $cardRepos;
    }

    public function getCatalog(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }


        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        $cards = $this->cardRepos->getAllCards();
        $categories = $this->cardRepos->getAllCategories();

        $greetings =  "Welcome, {$_SESSION['userName']}!";
        return [
            "../View/catalog.phtml",
            [
                'userGreetings' => $greetings,
                'cards' => $cards,
                'categories' => $categories

            ]
        ];
    }



}