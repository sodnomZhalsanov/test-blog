<?php
namespace App;

class App
{
    private array $routes = [
        'GET' => [
            "/signin" => [ handlers\UserController:: class, 'signIn'],
            "/main" => [ handlers\UserController:: class, 'getMain'],
            '/signup' => [ handlers\UserController:: class, 'signUp'],
            "/NotFound" => [handlers\UserController:: class, 'getNotFound']

        ],
        'POST' => [
            '/signin' => [ handlers\UserController:: class, 'signIn'],
            '/signup' => [ handlers\UserController:: class, 'signUp']


        ]
    ];

    public function run(): void {

        $handler = $this->route();

        list($obj, $method) = $handler;
        if(!is_object($obj)){
            $obj = new $obj();
        }
        $response = $obj->$method();

        list($view,$params) = $response;
        extract($params);


        ob_start();

        include $view;
        $content = ob_get_contents();
        $layout = file_get_contents('./views/layout.html');
        $result = str_replace('{content}', $content, $layout);

        ob_get_clean();

        echo $result;
    }

    private function route(): array {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler) {

            if (preg_match("#^$pattern#", $uri, $params)) {
                return $handler;
            }
        }

        return [handlers\UserController:: class, 'getNotFound'];

    }

    public function addRoute(string $route, string $handlerPath, string $method): void {
        $this->routes[$method][$route] = $handlerPath;
    }

    /*   private function doRouting(string $uri): string {
       if(preg_match("#/(?<route>[A-Za-z0-9-_]+)#", $uri, $matches ) and
           file_exists("./handlers/{$matches['route']}.php")) {

           return "./handlers/{$matches['route']}.php";

       }

       return "./views/NotFound.phtml";

   }
*/


}