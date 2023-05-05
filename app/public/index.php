<?php


require_once doRouting($_SERVER["REQUEST_URI"]);

function doRouting(string $uri): string {
    if(preg_match("([A-Za-z0-9-_]+)", $uri, $matches ) and
    file_exists("./handlers/{$matches[0]}.php")) {

        return "./handlers/{$matches[0]}.php";

    }

    return "./views/NotFound.phtml";

}





