<?php

use App\Container;
use App\Controller\UserController;
use App\Logger;
use App\LoggerInterface;
use App\Repository\UserRepository;
use App\Controller\CardController;
use App\Repository\CardRepository;
use App\Controller\CategoryController;
use App\Repository\CategoryRepository;
use App\Controller\BasketController;
use App\Repository\BasketRepository;
use App\Repository\BasketCardRepository;
use App\Service\BasketService;
use App\ViewRenderer;

return [
    UserRepository::class => function (Container $container) {
      $pdo = $container->get('db');

      $obj = new App\Repository\UserRepository($pdo);

      return $obj;
    },

    UserController::class => function (Container $container) {
      $userRepos = $container->get(UserRepository::class);
      $basketRepos = $container->get(BasketRepository::class);
      $renderer = $container->get(ViewRenderer::class);

      $obj = new UserController($userRepos, $basketRepos, $renderer);

      return $obj;
    },

    LoggerInterface::class => function () {
      return New Logger();
    },

    'db' => function (Container $container) {
      $settings = $container->get("settings");
      $host = $settings['db']['host'];
      $user = $settings['db']['user'];
      $database = $settings['db']['database'];
      $password = $settings['db']['password'];

      $pdo = new PDO("pgsql:host=$host;dbname=$database", $user, $password);



      return $pdo;
    },

    CardRepository::class => function (Container $container) {
      $pdo = $container->get('db');

      $obj = new CardRepository($pdo);

      return $obj;
    },

    CardController::class => function (Container $container) {
      $repos = $container->get(CardRepository::class);
      $renderer = $container->get(ViewRenderer::class);

      $obj = new CardController($repos, $renderer);

      return $obj;
    },

    CategoryRepository::class => function(Container $container) {
      $pdo = $container->get('db');

      $obj = new CategoryRepository($pdo);

      return $obj;
    },

    CategoryController::class => function (Container $container) {
      $repos = $container->get(CategoryRepository::class);
      $renderer = $container->get(ViewRenderer::class);

      $obj = new CategoryController($repos, $renderer);

      return $obj;
    },

    BasketController::class => function (Container $container) {
      $cardRepos = $container->get(CardRepository::class);
      $basketRepos = $container->get(BasketRepository::class);
      $basketCardRepos = $container->get(BasketCardRepository::class);
      $connection = $container->get('db');
      $basketService = $container->get(BasketService::class);
      $renderer = $container->get(ViewRenderer::class);


      $obj = new BasketController($cardRepos, $basketRepos,$basketService,$renderer, $basketCardRepos, $connection);

      return $obj;
    },

    BasketRepository::class => function (Container $container) {
      $connection = $container->get('db');

      return new BasketRepository($connection);
    },

    BasketCardRepository::class => function (Container $container) {
      $connection = $container->get('db');

      return new BasketCardRepository($connection);
    },

    BasketService::class => function (Container $container) {
    $connection = $container->get('db');
    $basketRepos = $container->get(BasketRepository::class);
    $basketCardRepos = $container->get(BasketCardRepository::class);

    return new BasketService($connection, $basketRepos, $basketCardRepos);
    }




];
