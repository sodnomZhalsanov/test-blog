<?php

require_once '../Autoloader.php';
$appRoot = dirname(__DIR__);
\App\Autoloader::register($appRoot);

use App\App;

$app = new App();

$app->get('/random', function () { return ['./views/layout.html', [] ]; } );

$app->run();

//












