<?php

class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {

    }

    public function create(string $fisrstname, string $lastname, string $email, string $pass, integer $cellphone)
    {
        $sth = $this->connection->prepare("INSERT INTO users (firstname, lastname,email,cellphone,password) 
        VALUES(:firstname,:lastname,:email,:cellphone,:password)");
        $sth->execute([
            'firstname' => $fisrstname,
            'lastname' => $lastname,
            'email' =>  $email,
            'cellphone' => $cellphone,
            'password' => password_hash($pass, PASSWORD_DEFAULT)
        ]);
    }

    public function getByEmail()
    {
        $query = $this->connect->prepare("select firstname, password, id from users where email = :email");
        $query->execute(['email' => $_POST['email']]);
        $result = $query->fetch();
        return $result;
    }

}