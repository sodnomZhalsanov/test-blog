<?php

use App\Container;
use App\Controller\UserController;
use App\Logger;
use App\LoggerInterface;
use App\Repository\UserRepository;

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
    }

];
