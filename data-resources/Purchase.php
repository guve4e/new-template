<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (HTTP_PATH . "/Http.php");

class Purchase implements IDO
{
    /**
     * @param int $id
     * @return mixed|object
     * @throws Exception
     */
    function get($id)
    {
        if (array_key_exists("order", $_SESSION))
            $order = $_SESSION['order'];
        else
            throw new Exception("Session is over!");

        $orderId = $order->id;

        $r = new Http();
        $res = $r->setWebService("inventory")
            ->setService("order")
            ->setParameter($orderId)
            ->setMethod("GET")
            ->needAuthorization()
            ->setExpectedHttpStatusCode(200)
            ->send();

        return $res;
    }

    public function create($id, stdClass $data = null): bool
    {
        // TODO: Implement create() method.
    }

    public function update($id, stdClass $data = null): bool
    {

    }

    public function add($id, stdClass $data): bool
    {

    }

    public function delete($id, stdClass $data = null): bool
    {

    }

    public function deleteAll($id, stdClass $data = null): bool
    {

    }
}