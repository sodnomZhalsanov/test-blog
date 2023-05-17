<?php

namespace App\Controller;
use App\Repository\CategoryRepository;

class CategoryController
{
    private CategoryRepository $categoryRepos;

    public function __construct(CategoryRepository $categoryRepos)
    {
        $this->categoryRepos = $categoryRepos;
    }

    public function getCatalog(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }


        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }
        $greetings =  "Welcome, {$_SESSION['userName']}!";
        $categories = $this->categoryRepos->getAllCategories();


        return [
            "../View/catalog.phtml",
            [
                'categories' => $categories,
                'userGreetings' => $greetings,
            ]
        ];
    }

}