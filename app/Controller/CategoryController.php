<?php

namespace App\Controller;
use App\Repository\CategoryRepository;
use App\ViewRenderer;

class CategoryController
{
    private CategoryRepository $categoryRepos;
    private ViewRenderer $renderer;

    public function __construct(CategoryRepository $categoryRepos, ViewRenderer $renderer)
    {
        $this->categoryRepos = $categoryRepos;
        $this->renderer = $renderer;
    }

    public function getCatalog(): ?string
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }


        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }
        $greetings =  "Welcome, {$_SESSION['userName']}!";
        $categories = $this->categoryRepos->getAllCategories();


        return $this->renderer->render(
            '../View/catalog.phtml',
            [
                'categories' => $categories,
                'userGreetings' => $greetings
            ]
        );
    }

}