<?php
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connect = new PDO("pgsql:host=db;dbname=dbname", "dbuser", "dbpwd");
    $errors = validateUser($_POST, $connect);

    if(empty($errors)){
        header("Location: ./views/main.php");
    }
}
function validateUser(array $data, PDO $connect): array {
        $errors = [];
        $email = $data["email"] ?? null;
        $pass = $data["pass"] ?? null;
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $errors += validatePasswordUser($pass, $hash);
        $errors += validateEmailUser($email, $connect);

        return $errors;

    }

function validatePasswordUser(string $pass, string $hash): array {
    $err = [];

    if(empty($pass)){
        $err['pass'] = "Please, enter your password";
        return $err;
    }



    if (!password_verify($pass, $hash)) {
        $err['password'] = "Your password is invalid, please enter correct password";
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


    $query = $connect->prepare("select email from users where email = :email");
    $query->execute(['email' => $email]);
    $value = $query->fetch(PDO::FETCH_COLUMN);

    if (empty($value)) {
        $err['email'] = "Your email is invalid, please enter correct email";
        return $err;
    }

    return $err;

}
