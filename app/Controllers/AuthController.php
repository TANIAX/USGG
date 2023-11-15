<?php

namespace App\Controllers;

use App\Entities\User;
use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\DTO\Request\Auth\LoginRequestDTO;

/**
 * 
 * This class represents the AuthController which is responsible for handling authentication related requests.
 * It extends the BaseController class and has two private properties: $userRepository and $newsRepository.
 * The constructor initializes these properties by getting the User and News repositories from the service container.
 * @author Guillaume cornez
 */
class AuthController extends BaseController
{
    private $userRepository;
    private $newsRepository;

    public function __construct()
    {
        $this->userRepository =  service('Repository','User');
    }

    public function login()
    {
        //Retrieve data from the request
        $userLogin = new LoginRequestDTO($this->getRequestInput($this->request));


        //If the form is submitted
        if($this->request->getPost())
        {
            //Validate the data
            $errors = $userLogin->validate();
            if(count($errors) > 0)
                return view('auth/login', ['errors' => $errors]);   

            //Check if the user exists
            $user = $this->userRepository->findOneBy(['email' => $userLogin->email],BaseRepository::RESULT_AS_CUSTOM,User::class);
            if(!$user)
                return view('auth/login', ['errors' => ['L\'adresse email ou le mot de passe est incorrect']]);

            //Check if the password is correct
            if(!password_verify($userLogin->password, $user->getPassword()))
                return view('auth/login', ['errors' => ['L\'adresse email ou le mot de passe est incorrect']]);
            
        }

        //Return the login form
        else
        {
            return view('auth/login');
        }
        
    }
}
