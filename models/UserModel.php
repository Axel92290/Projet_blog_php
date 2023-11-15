<?php
namespace Models;


/**
 * Class UserModel
 */
class UserModel
{
    /**
     * @var int The user's ID.
     */
    private $id;

    /**
     * @var string The user's first name.
     */
    private $firstname;

    /**
     * @var string The user's last name.
     */
    private $lastname;

    /**
     * @var string The user's email.
     */
    private $email;

    /**
     * @var string The user's password.
     */
    private $pwd;

    /**
     * @var DateTime The date and time the user was created.
     */
    private $createdAt;

    /**
     * @var DateTime The date and time the user was last updated.
     */
    private $updatedAt;

    /**
     * @var string The user's role (default is 'user').
     */
    private $role;

    /**
     * @var string The user's authentication token.
     */
    private $token;

    /**
     * @var DateTime The timestamp when the token expires.
     */
    private $expireAt;

    /**
     * Get the user's ID.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the user's ID.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the user's first name.
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the user's first name.
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get the user's last name.
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the user's last name.
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the user's email.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the user's email.
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get the user's password.
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * Set the user's password.
     * @param string $pwd
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    }

    /**
     * Get the date and time the user was created.
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the date and time the user was created.
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the date and time the user was last updated.
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the date and time the user was last updated.
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get the user's role.
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the user's role.
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get the user's authentication token.
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the user's authentication token.
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get the timestamp when the token expires.
     * @return DateTime
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * Set the timestamp when the token expires.
     * @param DateTime $expireAt
     */
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'email' => $this->getEmail(),
            'pwd' => $this->getPwd(),
            'role' => $this->getRole(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'token' => $this->getToken(),
            'expireAt' => $this->getExpireAt(),
        ];
    }
}