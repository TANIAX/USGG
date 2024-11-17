<?php

namespace App\Helpers;

use \Config\Services;

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
        return session()->get(self::USER_CONNECTED_SESSION_KEY) != null;
    }

    /**
     * Gets the connected user.
     *
     * @return object
     */
    public static function getUserConnected()
    {
        return session()->get(self::USER_CONNECTED_SESSION_KEY);
    }

    /**
     * Sets the connected user.
     *
     * @param object $user
     * @return void
     */
    public static function connectUser($user)
    {
        session()->set(self::USER_CONNECTED_SESSION_KEY,$user);
    }

    /**
     * Removes the connected user.
     *
     * @return void
     */
    public static function disconnectUser()
    {
        session()->remove(self::USER_CONNECTED_SESSION_KEY);
    }


}
