<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (HTTP_PATH . "/Http.php");

class Inventory implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
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
     * Update Resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update($id, stdClass $data = null): bool
    {

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

    /**
     * @param stdClass $shoppingCart
     * @return object
     * @throws Exception
     */
    public function getAvailableQuantities(stdClass $shoppingCart)
    {
        $r = new Http();
        $availableQuantities = $r->setWebService("inventory")
            ->setService("inventory")
            ->setMethod("GET")
            ->setParameter("CSerum/availableQuantities")
            ->setContentTypeApplicationJson()
            ->setDataToSend($shoppingCart)
            ->needAuthorization()
            ->setExpectedHttpStatusCode(200)
            ->send();

        return $availableQuantities;
    }

    /**
     * @param $sku
     * @return object
     * @throws Exception
     */
    public function getProductTypeBySku($sku)
    {
        $queryString = "inventoryName/CSerum/bySku?sku={$sku}";

        $r = new Http();
        $productType = $r->setWebService("inventory")
            ->setService("product")
            ->setMethod("GET")
            ->setParameter($queryString)
            ->needAuthorization()
            ->setExpectedHttpStatusCode(200)
            ->send();

        return $productType;
    }
}
