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


    public function create(User $user): void
    {
        $sth = $this->connection->prepare("INSERT INTO users (firstname, lastname,email,cellphone,password) 
        VALUES(:firstname,:lastname,:email,:cellphone,:password)");
        $sth->execute([
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' =>  $user->getEmail(),
            'cellphone' => $user->getPhoneNumber(),
            'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)
        ]);


    }

    public function getByEmail(array $data): User|bool
    {
        $query = $this->connection->prepare("select * from users where email = :email");
        $query->execute(['email' => $data['email']]);
        $result = $query->fetch();

        if (!empty($result)) {
            $user = new User(
                $result['firstname'],
                $result['lastname'],
                $result['email'],
                $result['password'],
                $result['cellphone']
            );
            $user->setId($result['id']);
            return $user;
        }

        return false;




    }

}