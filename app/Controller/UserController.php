<?php
namespace App\Controller;
use App\PDOconnection;
use PDO;

class UserController implements PDOconnection
{
    private PDO $connect;

    public function setConnection(PDO $connection): void
    {
        // TODO: Implement setConnection() method.

        $this->connect = $connection;
    }

    public function signUp(): array {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $errors = $this->validate($_POST , $this->connect);

            if (empty($errors)){
                $sth = $this->connect->prepare("INSERT INTO users (firstname, lastname,email,cellphone,password) 
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



        return [
            "./forms/signup.phtml",
            [
                'errors' => $errors
            ]
        ];

    }

    public function signIn(): array {
        session_start();
        $errors = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $errors = $this->validateUser($_POST, $this->connect);

            if(empty($errors)){
                $query = $this->connect->prepare("select firstname, password, id from users where email = :email");
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
    }

    public function getMain(): array {
        session_start();
        if (!isset($_SESSION['userId'])) {

            header("Location: /signin");
        }

        $greetings =  "Welcome, {$_SESSION['userName']}!";
        return [
            "./views/main.phtml",
            [
                'userGreetings' => $greetings
            ]
        ];

    }

    public function getNotFound(): array {
        return [
            "./views/NotFound.phtml",
            [

            ]

        ];
    }

    private function validateUser(array $data, PDO $connect): array {
        $errors = [];
        $email = $data["email"] ?? null;
        $pass = $data["pass"] ?? null;


        $errors += $this->validatePasswordUser($pass);
        $errors += $this->validateEmailUser($email, $connect);

        return $errors;

    }

    private function validatePasswordUser(string $pass): array {
        $err = [];

        if(empty($pass)){
            $err['pass'] = "Please, enter your password";
            return $err;
        }

        return $err;
    }

    private function validateEmailUser(string $email, PDO $connect): array {
        $err = [];

        if (empty($email)) {
            $err['email'] = "Please, enter your email";
            return $err;
        }


        return $err;

    }

    private function validate(array $data, PDO $connect): array {
        $errors = [];
        $email = $data["email"] ?? null;
        $pass = $data["pass"] ?? null;
        $firstName = $data["firstName"] ?? null;
        $lastName = $data["lastName"] ?? null;


        $errors += $this->validateFirstName($firstName);
        $errors += $this->validateLastName($lastName);
        $errors += $this->validatePassword($pass);
        $errors += $this->validateEmail($email, $connect);

        return $errors;

    }
    private function validateFirstName(string $firstName): array {
        $err = [];

        if (strlen($firstName) < 2 || strlen($firstName) > 49) {
            $err['firstName'] = "Your first name is invalid, please enter correct name. It must contain from 2 to 49 characters";
            return $err;
        }

        return $err;
    }

    private function validateLastName(string $lastName): array {
        $err = [];

        if (strlen($lastName) < 2 || strlen($lastName) > 49) {
            $err['lastName'] = "Your last name is invalid, please enter correct name. It must contain from 2 to 49 characters";
            return $err;
        }

        return $err;
    }

    private function validatePassword(string $pass): array {
        $err = [];

        if (strlen($pass) < 5 || strlen($pass) > 49) {
            $err['password'] = "Your password is invalid, please enter correct password. It must contain from 5 to 49 characters";
            return $err;
        }
        return $err;
    }

    private function validateEmail(string $email, PDO $connect): array {
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



}