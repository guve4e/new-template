<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (HTTP_PATH . "/Http.php");

class Braintree implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        $r = new Http();
        $res = $r->setWebService("payment")
            ->setService("braintree")
            ->setMethod("GET")
            ->setExpectedHttpStatusCode(200)
            ->send();

        return $res;
    }

    /**
     * Create resource.
     * @param int $id
     * @return mixed
     */
    public function create($id, stdClass $data = null): bool
    {

    }

    /**
     * Makes an Order
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update($id, stdClass $data = null): bool
    {
        // TODO clean this
        $shippingOption = $data->shippingOption;

        if ($shippingOption == "1")
            $cost = 12.47;
        else if ($shippingOption == "2")
            $cost = 8.45;
        else if ($shippingOption == "3")
            $cost = 3.77;
        else
            throw new Exception("shipping options are not set properly!");

        $shippingInfo = [
            "name" => $data->name,
            "phoneNumber" => $data->phoneNumber,
            "email" => $data->email,
            "cost" => $cost,
            "address" =>  [
                "number" => "312",
                "streetName" => $data->address1 . " " . $data->address2,
                "city" => $data->city,
                "state" => $data->state,
                "zip" => $data->zip
            ],
            "braintreeNonce" => $data->paymentmethodnonce
        ];

        $queryString = "user/{$id}";

        $r = new Http();
        $res = $r->setWebService("inventory")
            ->setService("order")
            ->setMethod("POST")
            ->setParameter($queryString)
            ->setDataToSend($shippingInfo)
            ->needAuthorization()
            ->setExpectedHttpStatusCode(201)
            ->send();

        $_SESSION['order']= $res;

        return true;
    }

    public function add($id, stdClass $data): bool
    {
        return true;
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete($id, stdClass $data = null): bool
    {

    }
}
