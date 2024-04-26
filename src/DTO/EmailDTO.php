<?php

namespace App\DTO;

use App\Entity\User;
use App\Repository\MessageRepository;
use DateTime;

class EmailDTO
{
    public string $email;

    public function getEmail(): string
    {
        return $this->email;
    }
}