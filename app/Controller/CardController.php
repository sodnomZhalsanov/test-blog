<?php

namespace App\Controller;


use App\Repository\CardRepository;
use App\Entity\Card;
use App\ViewRenderer;


class CardController
{
    private CardRepository $cardRepos;
    private ViewRenderer $renderer;

    public function __construct(CardRepository $cardRepos, ViewRenderer $renderer)
    {
        $this->cardRepos = $cardRepos;
        $this->renderer = $renderer;
    }

    public function getCards(int $categoryId): ?string
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }


        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        $cards = $this->cardRepos->getByCategory($categoryId);


        return $this->renderer->render(
            '../View/cards.phtml',
            [
                'cards' => $cards
            ]
        );
    }





}