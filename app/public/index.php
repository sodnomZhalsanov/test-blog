<?php

require_once '../Autoloader.php';
$appRoot = dirname(__DIR__);
\App\Autoloader::register($appRoot);

use App\App;



$dependencies = include "../Config/dependencies.php";
$settings = include "../Config/settings.php";
$data = array_merge($settings,$dependencies);

$container = new \App\Container($data);

$app = new App($container);
$app->get("/signup", [\App\Controller\UserController::class, 'signUp']);
$app->get("/signin", [\App\Controller\UserController::class, 'signIn']);
$app->get("/NotFound", [\App\Controller\UserController::class, 'getNotFound']);
$app->get('/catalog', [\App\Controller\CategoryController::class, 'getCatalog']);
$app->get('/catalog/\b(?<categoryId>[0-9]+)\b', [\App\Controller\CardController::class, 'getCards']);
$app->get("/basket", [\App\Controller\BasketController::class, 'getBasket']);

$app->post("/signup", [\App\Controller\UserController::class, 'signUp']);
$app->post("/signin", [\App\Controller\UserController::class, 'signIn']);
$app->post("/catalog", [\App\Controller\CategoryController::class, 'getCatalog']);
$app->post("/add", [\App\Controller\BasketController::class, 'addToBasket']);



$app->run();

//












