<?php
session_start();
if (isset($_SESSION['userId'])) {
    $greetings =  "Welcome, {$_SESSION['userName']}!";
    return [
        "./views/main.phtml",
        [
            'userGreetings' => $greetings
        ]
    ];
} else {
    header("Location: /signin");
}

