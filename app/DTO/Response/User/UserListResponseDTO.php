<?php

namespace App\DTO\Response\User;

use DateTime;

class UserListResponseDTO
{
    public int $id;
    public string $totem;
    public string $email;
    public string $name;
    public string $firstname;
    public DateTime $birthdate;
    public string $phone;
    public ?string $picture;
    public string $user_type;
}