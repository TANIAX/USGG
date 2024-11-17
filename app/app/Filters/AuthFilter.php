<?php

namespace App\Filters;

use App\Helpers\SessionHelper;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * This class implements the FilterInterface and is responsible for authenticating requests.
 * It checks if the user is authenticated and if he has the required roles to access the requested resource.
 * If the user is not authenticated, he is redirected to the login page.
 * If the user does not have the required roles, he is logged out and redirected to the login page.
 * 
 * @author: Guillaume cornez
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $args = null)
    {
        //Get the user from the session
        $user = SessionHelper::getUserConnected();

        //If the user is not connected, we redirect him to the login page
        if(!$user)
            return redirect()->to(base_url('/auth/login'));

        //If the user is connected, we check if he has the required roles (if any)
        if($args)
        {
            $roles = $user->getRolesAsStrings();
            $hasRole = false;

            foreach($args as $required_role)
            {
                if(in_array($required_role,$roles))
                {
                    $hasRole = true;
                    break;
                }
            }

            if(!$hasRole)
            {
                SessionHelper::disconnectUser();
                return redirect()->route(base_url('/auth/login'));
            }
   
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // You have nothing to see here, move along.
    }
}