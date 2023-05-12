<?php

require_once '../Autoloader.php';
$appRoot = dirname(__DIR__);
\App\Autoloader::register($appRoot);

use App\App;
use App\Repository\UserRepository;

$container = new \App\Container();

$container->set(\App\Controller\UserController::class, function (\App\Container $container) {
    $userRepos = $container->get(UserRepository::class);

    $obj = new \App\Controller\UserController($userRepos);

    return $obj;
});

$container->set(UserRepository::class, function () {
    $obj = new UserRepository(new PDO("pgsql:host=db;dbname=dbname", "dbuser", "dbpwd"));

    return $obj;
});

$app = new App($container);
$app->get("/ggg", ['fdfvxc', 'getFarm']);

$app->run();

//












