<?php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Basket;
use App\Repository\BasketRepository;
use App\ViewRenderer;


class UserController
{

    private UserRepository $userRepos;
    private BasketRepository $basketRepos;
    private  ViewRenderer $renderer;


    public function __construct(
        UserRepository $userRepos,
        BasketRepository $basketRepos,
        ViewRenderer $renderer
    )
    {
        $this->userRepos = $userRepos;
        $this->basketRepos = $basketRepos;
        $this->renderer = $renderer;
    }



    public function signUp(): ?string
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $errors = $this->validate($_POST);

            if (empty($errors)){
                $user = new User($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['pass'], $_POST['phoneNumber'] );
                $this->userRepos->create($user);
            }

        }



        return $this->renderer->render(
            '../View/forms/signup.phtml',
            ['errors' => $errors]
        );

    }

    public function signIn(): ?string
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $errors = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $errors = $this->validateUser($_POST);

            if(empty($errors)){
                $user = $this->userRepos->getByEmail($_POST);


                if (!empty($user) and password_verify($_POST['pass'],$user->getPassword())){
                    $_SESSION['userId'] = $user->getId();
                    $_SESSION['userName'] = $user->getFirstname();


                    header("Location: /catalog");
                } else {
                    $errors['log_error'] = "Your email or password is invalid, please try again";

                }

            }
        }

        return $this->renderer->render(
            '../View/forms/signin.phtml',
            ['errors' => $errors]
        );
    }


    public function getNotFound(): ?string
    {
        return $this->renderer->render(
            '../View/NotFound.phtml',
            []
        );
    }

    private function validateUser(array $data): array {
        $errors = [];
        $email = $data["email"] ?? null;
        $pass = $data["pass"] ?? null;


        $errors += $this->validatePasswordUser($pass);
        $errors += $this->validateEmailUser($email);

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

    private function validateEmailUser(string $email): array {
        $err = [];

        if (empty($email)) {
            $err['email'] = "Please, enter your email";
            return $err;
        }


        return $err;

    }

    private function validate(array $data): array {
        $errors = [];
        $email = $data["email"] ?? null;
        $pass = $data["pass"] ?? null;
        $firstName = $data["firstName"] ?? null;
        $lastName = $data["lastName"] ?? null;


        $errors += $this->validateFirstName($firstName);
        $errors += $this->validateLastName($lastName);
        $errors += $this->validatePassword($pass);
        $errors += $this->validateEmail($email);

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

    private function validateEmail(string $email): array {
        $err = [];

        if (empty($email)) {
            $err['email'] = "Please, enter your email";
            return $err;
        }


        $value = $this->userRepos->getByEmail($_POST);

        if (!empty($value)) {
            $err['email'] = "Email is already used";
            return $err;
        }

        return $err;

    }



}