<?php

namespace App\DTO\Response\User;

/**
 * Class UserListResponseDTO
 * 
 * Represents a response DTO for a user list.
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
     * @var string The user's type.
     */
    public string $user_type;

}