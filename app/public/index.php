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


    $query = $connect->prepare("select email from users where email = :log");
    $query->execute(['log' => $email]);
    $value = $query->fetch(PDO::FETCH_COLUMN);

    if (!empty($value)) {
        $err['email'] = "Email is already used";
        return $err;
    }

    return $err;

}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Bootstrap demo</title>
</head>
<body>
<div class="container-fluid">
    <form method="POST">
        <div class="mb-3">
            <label for="exampleInputFirstName" class="form-label">First name</label>
            <input name= "firstName" type="text" class="form-control" id="exampleInputFirstName">
        </div>
        <div>
            <?php
            if(array_key_exists('firstName', $errors)){
                echo "<font color = 'red'>{$errors['firstName']}</font>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="exampleInputLastName" class="form-label">Last name</label>
            <input name= "lastName" type="text" class="form-control" id="exampleInputLastName">
        </div>
        <div>
            <?php
            if(array_key_exists('lastName', $errors)){
                echo "<font color = 'red'>{$errors['lastName']}</font>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input name= "email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div>
            <?php
            if(array_key_exists('email', $errors)){
                echo "<font color = 'red'>{$errors['email']}</font>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="exampleInputPhone" class="form-label">Phone number</label>
            <input name= "phoneNumber" type="text" class="form-control" id="exampleInputPhone">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input name= "pass" type="password" class="form-control" id="exampleInputPassword1">
        </div>
        <div>
            <?php
            if(array_key_exists('password', $errors)){
                echo "<font color = 'red'>{$errors['password']}</font>";
            }
            ?>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>


