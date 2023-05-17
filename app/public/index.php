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
$app->get("/main", [\App\Controller\UserController::class, 'getMain']);
$app->get("/signin", [\App\Controller\UserController::class, 'signIn']);
$app->get("/NotFound", [\App\Controller\UserController::class, 'getNotFound']);
$app->get("/ggg", ['fdfvxc', 'getFarm']);
$app->post("/signup", [\App\Controller\UserController::class, 'signUp']);
$app->post("/signin", [\App\Controller\UserController::class, 'signIn']);
$app->get('/catalog', [\App\Controller\CategoryContoller::class, 'getCatalog']);


$app->run();

//












