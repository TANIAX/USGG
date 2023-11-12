<?php

namespace App\Controllers\API\V1;

use CodeIgniter\API\ResponseTrait;

class AuthController extends ApiController
{
    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
    }
    /*
     * Return JWT if user is authenticated
     * @author: Guillaume cornez
     * @return Object
     */
    public function Login()
    {
        throw new \Exception("Not implemented");
    }
}
