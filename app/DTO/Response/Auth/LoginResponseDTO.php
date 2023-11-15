<?php

namespace App\DTO\Response\Auth;

/**
 * @author: Guillaume cornez
 */
class LoginResponseDTO
{
    public $access_token;
    public $refresh_token;
    public $user;

    public function __construct($access_token = "", $refresh_token = "", $user = null)
    {
        //Attrib parameters to class properties
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
        $this->user = $user;

        //If parameter is empty, we unset the values
        if ($access_token == "") 
            unset($this->access_token);

        if ($refresh_token == "")
            unset($this->refresh_token);

        if ($user == null)
            unset($this->user);
        
    }
}