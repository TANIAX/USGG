<?php

namespace App\Helpers;


/**
 * A helper class for handling session operations.
 *
 * @author Guillaume cornez
 */
class SessionHelper
{
    const USER_CONNECTED_SESSION_KEY = "user_connected";

    /**
     * Checks if the user is connected.
     *
     * @return boolean
     */
    public static function isUserConnected()
    {
        return isset($_SESSION[self::USER_CONNECTED_SESSION_KEY]);
    }

    /**
     * Gets the connected user.
     *
     * @return object
     */
    public static function getUserConnected()
    {
        return $_SESSION[self::USER_CONNECTED_SESSION_KEY];
    }

    /**
     * Sets the connected user.
     *
     * @param object $user
     * @return void
     */
    public static function connectUser($user)
    {
        $_SESSION[self::USER_CONNECTED_SESSION_KEY] = $user;
    }

    /**
     * Removes the connected user.
     *
     * @return void
     */
    public static function disconnectUser()
    {
        unset($_SESSION[self::USER_CONNECTED_SESSION_KEY]);
    }


}
