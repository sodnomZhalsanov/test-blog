<?php

namespace App;

class App
{
    private array $routes = [
        'GET' => [
            "/(?<route>[A-Za-z0-9-_]+)" => "./handlers/signin.php"
        ],
        'POST' => [
            '/signin' => [UserController::class, 'signin']

        ]
    ];

    public function run(): void {

        $handler = $this->doRouting($_SERVER["REQUEST_URI"]);

        list($view, $params) = require_once $handler;

        extract($params);


        ob_start();

        include $view;
        $content = ob_get_contents();
        $layout = file_get_contents('./views/layout.html');
        $result = str_replace('{content}', $content, $layout);

        ob_get_clean();

        echo $result;
    }

    private function doRouting(string $uri): string {
        if(preg_match("#/(?<route>[A-Za-z0-9-_]+)#", $uri, $matches ) and
            file_exists("./handlers/{$matches['route']}.php")) {

            return "./handlers/{$matches['route']}.php";

        }

        return "./views/NotFound.phtml";

    }

    private function route(): ?string {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler) {

            if (preg_match("#$pattern#", $uri, $params) {
                if (file_exists($handler)) {
                    return $handler;
                }
            }
        }

        return './views/NotFound.phtml';

    }

    public function addRoute(string $route, string $handlerPath, string $method): void {
        $this->routes[$method][$route] = $handlerPath;
    }


}