<?php

class App
{
    private function doRouting(string $uri): string {
        if(preg_match("#/(?<route>[A-Za-z0-9-_]+)#", $uri, $matches ) and
            file_exists("./handlers/{$matches['route']}.php")) {

            return "./handlers/{$matches['route']}.php";

        }

        return "./views/NotFound.phtml";

    }
    function run(): void {

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


}