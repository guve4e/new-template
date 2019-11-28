<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (LIBRARY_PATH . "/user-session/SessionHelper.php");
require_once (LIBRARY_PATH . "/exceptions/HttpException.php");
require_once (HTTP_PATH . "/Http.php");

require_once ("Cart.php");

class User implements IDO
{
    private function isBot(): bool
    {
        $parts = explode("/", $_SERVER['HTTP_HOST']);
        $host = $parts[0];

        return preg_match('#[0-9]#', $host);
    }

    /**
     * Get resource.
     * @param int $id
     * @param stdClass $fields
     * @return mixed
     */
    public function authenticate($id, stdClass $fields)
    {
        // Fake call to back end
        $authenticated = $fields->login_username == "root" && $fields->login_password == "pass";
        if ($authenticated) {
            $_SESSION['authenticated_user'] = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        try {
            // id parameter is the hash retrieved from the cookie
            $queryString = "getVisitorByHash/{$id}";

            $r = new Http();

            $r->setWebService("user")
                ->setService("visitors")
                ->setParameter($queryString)
                ->setMethod("GET")
                ->needAuthorization()
                ->setExpectedHttpStatusCode(200);

            $user = $r->send();

            return $user;

        } catch (Exception $e) {
            print ($e->getMessage());
            print("\nCouldn't create User!\n");
            return null;
        }
    }

    /**
     * Create resource.
     * @param int $id
     * @param stdClass|null $data
     * @return mixed
     */
    public function create($id, stdClass $data = null): bool
    {
        try {
            if($this->isBot())
                throw new Exception();

            $r = new Http();
            $r->setWebService("user")
                ->setService("visitors")
                ->setParameter($id)
                ->setMethod("POST")
                ->needAuthorization()
                ->setExpectedHttpStatusCode(201);
            $user = $r->send();

            if (!isset($user))
                throw new Exception("User was not retrieved from services!\n");
            $_SESSION["user"] = $user;

            $c = new Cart();
            $c->create($user->id);

            return true;
        } catch (Exception $e) {
            print ($e->getMessage());
            print("\nCouldn't create User!\n");
            return false;
        }
    }

    /**
     * Update Resource.
     * @param int $id
     * @param stdClass|null $data
     * @return mixed
     */
    public function update($id, stdClass $data = null): bool
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete Resource.
     * @param int $id
     * @param stdClass|null $data
     * @return mixed
     */
    public function delete($id, stdClass $data = null): bool
    {
        // TODO: Implement delete() method.
    }
}