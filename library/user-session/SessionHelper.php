<?php

require_once (UTILITY_PATH . "/Logger.php");
require_once (UTILITY_PATH . "/FileManager.php");
require_once (UTILITY_PATH . "/JsonLoader.php");
require_once (CONFIGURATION_PATH. "/SiteConfigurationLoader.php");


class SessionHelper
{
    /**
     * @throws Exception
     */
    private static function getSessionToken()
    {
        $jsonLoader = new SiteConfigurationLoader(new FileManager());
        $configuration = $jsonLoader->getData();
        $sessionToken = $configuration->session->key;

        return $sessionToken;
    }

    /**
     * Saves user object into $_SESSION
     * super-global.
     * @param stdClass $user
     * @throws Exception
     */
    public static function saveUserInSession(stdClass $user)
    {

        $sessionToken = self::getSessionToken();
        // save info in session
        $_SESSION[$sessionToken] = $user;
    }

    /**
     * @return null
     * @throws Exception
     */
    public static function getUserFromSession()
    {
        $sessionToken = self::getSessionToken();

        $user = null;
        if(isset($_SESSION[$sessionToken]))
            $user = $_SESSION[$sessionToken];
        else
            throw new Exception("User is not SET!");

        return $user;
    }

    public static function isAuthenticated(): bool
    {
        if(isset($_SESSION["authenticated_user"]) && ($_SESSION["authenticated_user"] == true))
            return true;
        else
            return false;
    }

    /**
     * Log out
     */
    public static function logout() {
        // delete cookie
        setcookie(session_name(), '', time() - 2592000, '/');
        // remove all session variables
        session_unset();
        $_SESSION = array();
        // destroy the session
        session_destroy();
    }
}