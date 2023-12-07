<?php

namespace App\Controllers;

use App\Entities\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Google\Service\Oauth2;
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
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;
    private \Google\Client $googleClient;

    public function __construct()
    {
        $this->userRepository = service('Repository', 'User');
        $this->roleRepository = service('Repository', 'Role');
        $this->initGoogleClient();
    }

    /**
     * 
     * This method handles the login functionality of the application.
     * If the user is already connected, they are redirected to the home page.
     * If the request is a POST request, the data is validated and the user is logged in.
     * If the login is successful, the user is stored in the session and redirected to the home page.
     */
    public function login()
    {
        $authUrl = $this->googleClient->createAuthUrl();

        //If the user is already connected, we redirect him to the home page
        if ($this->session->get(SessionHelper::USER_CONNECTED_SESSION_KEY))
            return redirect()->to('/');


        //If the request is a POST request, we validate the data and try to log the user in.
        if ($this->request->getPost()) {
            //Retrieve data from the request & validate it
            $userLogin = new LoginRequestDTO($this->getRequestInput($this->request));
            $errors = $userLogin->validate();
            if (count($errors) > 0)
                return view('auth/login', ['errors' => $errors, 'authUrl' => $authUrl]);

            //Get the user from the database
            $user = $this->userRepository->getFullUserBy(['email' => $userLogin->email], BaseRepository::RESULT_AS_CUSTOM, User::class);
            if (!$user)
                return view('pages/auth/login', ['errors' => ['L\'adresse email ou le mot de passe est incorrect'], 'authUrl' => $authUrl]);
            if (!password_verify($userLogin->password, $user->getPassword()))
                return view('pages/auth/login', ['errors' => ['L\'adresse email ou le mot de passe est incorrect'], 'authUrl' => $authUrl]);


            //Store the user in the session
            $this->session->set(SessionHelper::USER_CONNECTED_SESSION_KEY, $user->getRestrictedUser());
            return redirect()->to('/');
        }

        return view('pages/auth/login', [
            'authUrl' => $authUrl
        ]);
    }


    /**
     * Handles the callback after a user logs in with Google.
     */
    public function loginWithGoogleCallback()
    {
        $email = "";

        // If the "code" parameter is set in the GET request, fetch the access token and retrieve the user's email from the Google API.
        if (isset($_GET["code"])) {
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET["code"]);

            if (!isset($token['error'])) {
                $this->googleClient->setAccessToken($token['access_token']);
                $google_service = new Oauth2($this->googleClient);
                $data = $google_service->userinfo->get();
                if (!empty($data['email']))
                    $email = $data['email'];
            }
        }

        //Get the user from the database        
        $user = $this->userRepository->getFullUserBy(['email' => $email], BaseRepository::RESULT_AS_CUSTOM, User::class);
        if (!$user) {
            return view('pages/auth/login', [
                'errors' => ['L\'adresse email est inconnue de l\'application'],
                'authUrl' => $this->googleClient->createAuthUrl()
            ]);
        }

        //Store the user in the session
        $this->session->set(SessionHelper::USER_CONNECTED_SESSION_KEY, $user->getRestrictedUser());
        return redirect()->to('/');
    }

    /**
     * Logout the user and redirect to the login page.
     */
    public function logout()
    {
        $this->session->remove(SessionHelper::USER_CONNECTED_SESSION_KEY);
        return redirect()->to('/auth/login');
    }

    /**
     * Initializes the Google Client.
     *
     * This method creates a new instance of the Google\Client class and sets the necessary configuration options.
     * It sets the client ID, client secret, redirect URL, and adds the 'email' scope.
     * The 'verify' option is set to false to disable SSL verification.
     */
    private function initGoogleClient()
    {
        $this->googleClient = new \Google\Client(['verify' => false]);
        $this->googleClient->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $this->googleClient->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $this->googleClient->setRedirectUri(getenv('GOOGLE_REDIRECT_URL'));
        $this->googleClient->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $this->googleClient->addScope('email');
    }
}
