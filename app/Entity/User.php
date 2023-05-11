<?php
namespace App\Entity;
class User
{
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $pass;
    private ?int $phone;

    public function __construct(int $id, string $firstname, string $lastname,string $email, string $pass, ?int $phone)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->pass = $pass;
        $this->phone = $phone;
    }


}