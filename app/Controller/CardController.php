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

    public function getCards(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }


        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        $cards = $this->cardRepos->getAllCards();


        return [
            "../View/catalog.phtml",
            [

                'cards' => $cards,
            ]
        ];
    }





}