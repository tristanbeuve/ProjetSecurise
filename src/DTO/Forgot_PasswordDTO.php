<?php

namespace App\DTO;

use App\Entity\User;
use App\Repository\MessageRepository;
use DateTime;

class Forgot_PasswordDTO
{
    public $password;
    public $confirmPassword;

}