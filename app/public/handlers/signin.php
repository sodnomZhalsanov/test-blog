<?php
session_start();
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connect = new PDO("pgsql:host=db;dbname=dbname", "dbuser", "dbpwd");
    $errors = validateUser($_POST, $connect);

    if(empty($errors)){
        $query = $connect->prepare("select firstname, password, id from users where email = :email");
        $query->execute(['email' => $_POST['email']]);
        $result = $query->fetch();


        if (!empty($result) and password_verify($_POST['pass'],$result['password'])){
            $_SESSION['userId'] = $result['id'];
            $_SESSION['userName'] = $result['firstname'];

            header("Location: /main");
        } else {
            $errors['log_error'] = "Your email or password is invalid, please try again";

        }

    }
}

return [
    './forms/signin.phtml',
    [
        'errors' => $errors,
    ]
    ];

function validateUser(array $data, PDO $connect): array {
    $errors = [];
    $email = $data["email"] ?? null;
    $pass = $data["pass"] ?? null;


    $errors += validatePasswordUser($pass);
    $errors += validateEmailUser($email, $connect);

    return $errors;

    }

function validatePasswordUser(string $pass): array {
    $err = [];

    if(empty($pass)){
        $err['pass'] = "Please, enter your password";
        return $err;
    }

    return $err;
}

function validateEmailUser(string $email, PDO $connect): array {
    $err = [];

    if (empty($email)) {
        $err['email'] = "Please, enter your email";
        return $err;
    }


    return $err;

}
