<?php

namespace App\Controller;

use App\Repository\CardRepository;

class CardController
{
    private CardRepository $cardRepos;

    public function __construct(CardRepository $cardRepos)
    {
        $this->cardRepos = $cardRepos;
    }



}