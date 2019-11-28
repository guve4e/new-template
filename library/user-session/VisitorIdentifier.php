<?php
/**
 * Used for user identification.
 *
 * IdentifyUser holding two properties:
 * 1. cookie object (Cookie Class)
 * 2. hash (string produced by combining the
 * $_SERVER['REMOTE_ADDR'] + $_SERVER['HTTP_USER_AGENT'] + $_SERVER['HTTP_ACCEPT']
 * This class looks trough the users machine and checks for cookie with name "web_user".
 * If NOT found, retrieves the needed info from _SERVER super-global and produces a hash.
 * This hash is stored in the users machine in cookie and it adds the user in a table.
 * If the cookie is already there it retrieves the has and asks the web-api for user with this hash.
 */


require_once ("CookieSetter.php");
require_once ("SessionHelper.php");
require_once (DATA_RESOURCE_PATH . "/User.php");

class VisitorIdentifier
{
    /**
     * @var object
     * Makes a cookie
     * on client's computer.
     */
    private $cookieSetter;

    /**
     *
     * @var string
     * Hash produced for each
     * identified user.
     */
    private $userHash;

    /**
     * @var
     * User object retrieved
     * from back end form.
     */
    private $user;

    /**
     * @var null
     * Instance variable
     * used to simulate Singleton.
     */
    private static $instance = null;

    /**
     * Identify constructor.
     * @param CookieSetter $cookieSetter
     * @param User $user
     * @throws Exception
     */
    private function __construct(CookieSetter $cookieSetter, User $user)
    {
        if (!isset($cookieSetter) || !isset($user))
            throw new Exception("Bad parameter in IdentifyUser constructor!");

        // initialize CookieSetter and User Object
        $this->cookieSetter = $cookieSetter;
        $this->user = $user;
    }

    /**
     * Calls User DAO
     * to retrieve the user information.
     * @throws Exception
     */
    private function retrieveUser()
    {
        return $this->user->get($this->userHash);
    }

    /**
     * Calls User DAO to make new User.
     * @access private
     * @throws Exception
     */
    private function makeNewUser()
    {
        // set up new cookie on
        // client's computer
        $this->cookieSetter->setCookie();

        // get the hash
        $hash = $this->cookieSetter->getHash();

        // TODO should you pass empty object
        // Try create user. If not successful, there is
        // no reason to proceed. It is a fatal Exception
        if (!$this->user->create($hash))
            throw new Exception("Something went wrong! Please contact customer support. Code: 17786");
    }

    /**
     * Checks client's computer for a cookie file.
     * if there is a file:
     *    - Attempts to retrieve user.
     *          If not successful
     *              - Create cookie file and call recursively
     * else:
     *
     * @throws Exception
     */
    public function identify()
    {
        // new user
        if (!isset($_COOKIE[$this->cookieSetter->getCookieName()]))
            $this->makeNewUser();

        // get hash from cookie
        $this->userHash = $_COOKIE[$this->cookieSetter->getCookieName()];

        $user = $this->retrieveUser();

        if (!isset($user))
        {
            if (!$this->user->create($this->userHash))
                throw new Exception("Something went wrong! Please contact customer support. Code: 17786");
            $this->identify();
        }
        else
        {
            $this->user = $user;
        }

        // save to session
        SessionHelper::saveUserInSession($this->user);
    }

    /**
     * Singleton.
     * @access public
     * @param CookieSetter $cookieSetter
     * @param User $user
     * @return VisitorIdentifier
     * @throws Exception
     */
    public static function IdentifyVisitor(CookieSetter $cookieSetter, User $user) : VisitorIdentifier
    {
        if (self::$instance === null) {
            self::$instance = new VisitorIdentifier($cookieSetter, $user);
        }

        return self::$instance;
    }

    public function __destruct()
    {
        unset($this->cookieSetter);
        unset($this->userHash);
        unset($this->http);
        self::$instance = null;
    }
}