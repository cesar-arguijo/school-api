<?php

namespace App\Api\DTO;

use Symfony\Component\Uid\Uuid;

class UserRegisteredDTO
{
    private Uuid $userId;
    private string $username;
    private string $email;

    public function __construct(Uuid $userId, string $username, string $email)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
