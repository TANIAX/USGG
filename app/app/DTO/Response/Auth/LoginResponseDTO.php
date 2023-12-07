<?php

namespace App\DTO\Response\Auth;

/**
 * Class LoginResponseDTO
 * 
 * Represents the response data for a login operation.
 * @author: Guillaume cornez
 */
class LoginResponseDTO
{
    /**
     * @var string|null The access token.
     */
    public $access_token;

    /**
     * @var string|null The refresh token.
     */
    public $refresh_token;

    /**
     * @var mixed|null The user data.
     */
    public $user;

    /**
     * LoginResponseDTO constructor.
     *
     * @param string $access_token The access token.
     * @param string $refresh_token The refresh token.
     * @param mixed|null $user The user data.
     */
    public function __construct($access_token = "", $refresh_token = "", $user = null)
    {
        // Assign parameters to class properties
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
        $this->user = $user;

        // If a parameter is empty, unset the corresponding property
        if ($access_token == "") 
            unset($this->access_token);

        if ($refresh_token == "")
            unset($this->refresh_token);

        if ($user == null)
            unset($this->user);
    }
}