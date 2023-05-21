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

return [
    UserRepository::class => function (Container $container) {
      $pdo = $container->get('db');

      $obj = new App\Repository\UserRepository($pdo);

      return $obj;
    },

    UserController::class => function (Container $container) {
      $userRepos = $container->get(UserRepository::class);
      $basketRepos = $container->get(BasketRepository::class);

      $obj = new UserController($userRepos, $basketRepos);

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

      $obj = new CardController($repos);

      return $obj;
    },

    CategoryRepository::class => function(Container $container) {
      $pdo = $container->get('db');

      $obj = new CategoryRepository($pdo);

      return $obj;
    },

    CategoryController::class => function (Container $container) {
      $repos = $container->get(CategoryRepository::class);

      $obj = new CategoryController($repos);

      return $obj;
    },

    BasketController::class => function (Container $container) {
      $cardRepos = $container->get(CardRepository::class);
      $basketRepos = $container->get(BasketRepository::class);
      $basketCardRepos = $container->get(BasketCardRepository::class);
      $connection = $container->get('db');

      $obj = new BasketController($cardRepos, $basketRepos, $basketCardRepos, $connection);

      return $obj;
    },

    BasketRepository::class => function (Container $container) {
      $connection = $container->get('db');

      return new BasketRepository($connection);
    },

    BasketCardRepository::class => function (Container $container) {
      $connection = $container->get('db');

      return new BasketCardRepository($connection);
    }




];
