<?php
namespace App;

use App\Exception\ContainerException;

class App
{
    private array $routes = [
        'GET' => [


        ],
        'POST' => [

        ]
    ];

    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

    }

    public function run(): void {
        try {

        $handler = $this->route();

        list($handler, $params) = $handler;

        if(is_array($handler)){
            list($obj, $method) = $handler;

            if(!is_object($obj)){
                    $obj = $this->container->get($obj);
            }

            if (empty($params)) {
                $response = $obj->$method();
            } else {
                $response = $obj->$method(...$params);
            }

        } else{
            $response = $handler();
        }


        echo $response;

    } catch (\Throwable $e) {
           $logger = $this->container->get(LoggerInterface::class);

           $context = [
               'exception' => $e->getMessage(),
               'file' => $e->getFile(),
               'line' => $e->getLine()
           ];

           $logger->error('Unsuccessful query processing', $context);

           require_once "../View/NotFound.phtml";



        }
}

    private function route(): array|callable {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler) {

            if (preg_match("#^$pattern$#", $uri, $params)) {
                foreach ($params as $key => $value) {
                    if ($key === 0 || intval($key)){
                        unset($params[$key]);
                    }
                }


                $params = array_values($params);

                return [$handler, $params];
            }
        }

        return [[ \App\Controller\UserController:: class, 'getNotFound'],[]];

    }


    public function get(string $route, array|callable $handler): void {
        $this->routes['GET'][$route] = $handler;
    }

    public function post(string $route, array|callable $handler): void {
        $this->routes['POST'][$route] = $handler;

    }



}