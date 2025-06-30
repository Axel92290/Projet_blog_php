<?php


namespace Models;

use DateTime;

class UserModel
{
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $pwd;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private string $role;
    private string $token;
    private DateTime $expireAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPwd(): string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): void
    {
        $this->pwd = $pwd;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getExpireAt(): DateTime
    {
        return $this->expireAt;
    }

    public function setExpireAt(DateTime $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->getId(),
            'firstname' => $this->getFirstname(),
            'lastname'  => $this->getLastname(),
            'email'     => $this->getEmail(),
            'pwd'       => $this->getPwd(),
            'role'      => $this->getRole(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'token'     => $this->getToken(),
            'expireAt'  => $this->getExpireAt(),
        ];
    }
}
