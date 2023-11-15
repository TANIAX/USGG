<?php

namespace App\Controllers;

use Config\Services;
use App\Entities\Role;
use App\Entities\User;
use App\Helpers\SessionHelper;
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
    private $roleRepository;

    

    public function __construct()
    {
        $this->userRepository =  service('Repository','User');
        $this->roleRepository =  service('Repository','Role');
    }

    /**
     * 
     * This method handles the login functionality of the application.
     * If the user is already connected, they are redirected to the home page.
     * If the request is a POST request, the data is validated and the user is logged in.
     * If the login is successful, the user is stored in the session and redirected to the home page.
     * 
     * @author Guillaume cornez
     */
    public function login()
    {        
        //If the user is already connected, we redirect him to the home page
        if($this->session->get(SessionHelper::USER_CONNECTED_SESSION_KEY))
            return redirect()->to('/');
        

        //If the request is a POST request, we validate the data and try to log the user in.
        if($this->request->getPost())
        {
            //Retrieve data from the request & validate it
            $userLogin = new LoginRequestDTO($this->getRequestInput($this->request));
            $errors = $userLogin->validate();
            if(count($errors) > 0)
                return view('auth/login', ['errors' => $errors]);   

            $user = $this->userRepository->getFullUserBy(['email'=> $userLogin->email],BaseRepository::RESULT_AS_CUSTOM,User::class);
            if(!$user)
                return view('auth/login', ['errors' => ['L\'adresse email ou le mot de passe est incorrect']]);
            if(!password_verify($userLogin->password, $user->getPassword()))
                return view('auth/login', ['errors' => ['L\'adresse email ou le mot de passe est incorrect']]);


            //Store the user in the session
            $this->session->set(SessionHelper::USER_CONNECTED_SESSION_KEY, $user->getRestrictedUser());
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function logout()
    {
        $this->session->remove(SessionHelper::USER_CONNECTED_SESSION_KEY);
        return redirect()->to('/auth/login');
    }
}
