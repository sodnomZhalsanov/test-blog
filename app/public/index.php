<?php


if ($_SERVER['REQUEST_URI'] === '/signup'){
    require_once "./handlers/signup.php";
    require_once "./forms/signup.phtml";
}
if ($_SERVER['REQUEST_URI'] === '/signin'){
    require_once  "./handlers/signin.php";
    require_once "./forms/signin.phtml";
}

?>




