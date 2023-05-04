<?php
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connect = new PDO("pgsql:host=db;dbname=dbname", "dbuser", "dbpwd");
    $errors = validate($_POST , $connect);

    if (empty($errors)){
        $sth = $connect->prepare("INSERT INTO users (firstname, lastname,email,cellphone,password) 
        VALUES(:firstname,:lastname,:email,:cellphone,:password)");
        $sth->execute([
            'firstname' => $_POST['firstName'],
            'lastname' => $_POST['lastName'],
            'email' => $_POST['email'],
            'cellphone' => $_POST['phoneNumber'],
            'password' => password_hash($_POST["pass"], PASSWORD_DEFAULT)
        ]);
    }

}
function validate(array $data, PDO $connect): array {
    $errors = [];
    $email = $data["email"] ?? null;
    $pass = $data["pass"] ?? null;
    $firstName = $data["firstName"] ?? null;;
    $lastName = $data["lastName"] ?? null;


    $errors += validateFirstName($firstName);
    $errors += validateLastName($lastName);
    $errors += validatePassword($pass);
    $errors += validateEmail($email, $connect);

    return $errors;

}
function validateFirstName(string $firstName): array {
    $err = [];

    if (strlen($firstName) < 2 || strlen($firstName) > 49) {
        $err['firstName'] = "Your first name is invalid, please enter correct name. It must contain from 2 to 49 characters";
        return $err;
    }

    return $err;
}

function validateLastName(string $lastName): array {
    $err = [];

    if (strlen($lastName) < 2 || strlen($lastName) > 49) {
        $err['lastName'] = "Your last name is invalid, please enter correct name. It must contain from 2 to 49 characters";
        return $err;
    }

    return $err;
}

function validatePassword(string $pass): array {
    $err = [];

    if (strlen($pass) < 5 || strlen($pass) > 49) {
        $err['password'] = "Your password is invalid, please enter correct password. It must contain from 5 to 49 characters";
        return $err;
    }
    return $err;
}

function validateEmail(string $email, PDO $connect): array {
    $err = [];

    if (empty($email)) {
        $err['email'] = "Please, enter your email";
        return $err;
    }


    $query = $connect->prepare("select email from users where email = :email");
    $query->execute(['email' => $email]);
    $value = $query->fetch(PDO::FETCH_COLUMN);

    if (!empty($value)) {
        $err['email'] = "Email is already used";
        return $err;
    }

    return $err;

}
