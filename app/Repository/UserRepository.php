<?php
namespace App\Repository;
use PDO;
use App\Entity\User;


class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }


    public function create(string $firstname, string $lastname, string $email, string $pass, ?int $cellphone): void
    {
        $sth = $this->connection->prepare("INSERT INTO users (firstname, lastname,email,cellphone,password) 
        VALUES(:firstname,:lastname,:email,:cellphone,:password)");
        $sth->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' =>  $email,
            'cellphone' => $cellphone,
            'password' => password_hash($pass, PASSWORD_DEFAULT)
        ]);


    }

    public function getByEmail(array $data): User|bool
    {
        $query = $this->connection->prepare("select * from users where email = :email");
        $query->execute(['email' => $data['email']]);
        $result = $query->fetch();

        if (!empty($result)) {
            $user = new User(
                $result['id'],
                $result['firstname'],
                $result['lastname'],
                $result['email'],
                $result['password'],
                $result['cellphone']
            );
            return $user;
        }

        return false;




    }

}