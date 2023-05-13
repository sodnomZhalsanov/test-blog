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

    public function __construct(string $firstname, string $lastname,string $email, string $pass, ?int $phone)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->pass = $pass;
        $this->phone = $phone;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getFirstname(): string
    {
        return $this->firstname;
    }


    public function getLastname(): string
    {
        return $this->lastname;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function getPhoneNumber(): int
    {
        return $this->phone;
    }


    public function getPassword(): string
    {
        return $this->pass;
    }


}