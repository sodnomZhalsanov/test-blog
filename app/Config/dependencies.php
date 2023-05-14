<?php

use App\Container;
use App\Controller\UserController;
use App\Logger;
use App\LoggerInterface;
use App\Repository\UserRepository;
use App\Controller\CardController;
use App\Repository\CardRepository;

return [
    UserRepository::class => function (Container $container) {
      $pdo = $container->get('db');

      $obj = new App\Repository\UserRepository($pdo);

      return $obj;
    },

    UserController::class => function (Container $container) {
      $repos = $container->get(UserRepository::class);

      $obj = new UserController($repos);

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
    }



];
