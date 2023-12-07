<?php

namespace App\DTO\Response\User;

use DateTime;

/**
 * Class UserListResponseDTO
 * 
 * Represents a response DTO for a list of users.
 */
class UserListResponseDTO
{
    /**
     * @var int The user ID.
     */
    public int $id;

    /**
     * @var string The user's totem.
     */
    public string $totem;

    /**
     * @var string The user's email.
     */
    public string $email;

    /**
     * @var string The user's name.
     */
    public string $name;

    /**
     * @var string The user's first name.
     */
    public string $firstname;

    /**
     * @var DateTime The user's birthdate.
     */
    public DateTime $birthdate;

    /**
     * @var string The user's phone number.
     */
    public string $phone;

    /**
     * @var string|null The URL of the user's picture.
     */
    public ?string $picture;

    /**
     * @var mixed The user's type.
     * @see UserRepository for more information about the type of this property.
     */
    public mixed $user_type;
}