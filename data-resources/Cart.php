<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (HTTP_PATH . "/Http.php");

class Cart implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        try {
            $r = new Http();

                $queryString = "byUserId/{$id}";

                $r->setWebService("shoppingCart")
                    ->setService("shoppingCart")
                    ->setParameter($queryString)
                    ->setMethod("GET")
                    ->needAuthorization()
                    ->setExpectedHttpStatusCode(200);

            $cart = $r->send();

            return $cart;

        } catch (Exception $e) {
            print ($e->getMessage());
            print("\nCouldn't create User!\n");
            return null;
        }
    }

    /**
     * TODO: create method should have default id parameter
     * Create resource.
     * @param int $id
     * @return mixed
     */
    public function create($id, stdClass $data = null): bool
    {
        try {
            $r = new Http();
            $r->setWebService("shoppingCart")
                ->setService("shoppingCart")
                ->setParameter($id)
                ->setMethod("POST")
                ->needAuthorization()
                ->setExpectedHttpStatusCode(201);

            $cart = $r->send();
            $_SESSION["shoppingCart"] = $cart;

            return true;
        } catch (Exception $e) {
            print ($e->getMessage());
            print("\nCouldn't create Shopping Cart!\n");
            return false;
        }
    }

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update($id, stdClass $data = null): bool
    {
        $productId = $data->productId;
        $newQty = $data->newQty;

        $queryString = "user/{$id}/updateShoppingCart?product_id={$productId }&qty={$newQty}";


        $productType = new StdClass;
        $productType->sku = "1001"; // TODO: real sku
        $productType->name = "Some Name";
        $productType->price = 12.48;
        $productType->description = "Some Description";
        $productType->directions = "Some Direction";
        $productType->imagePaths = [];
        $productType->totalPrice = 0;
        $productType->quantity = 0;

        $r = new Http();
        $res = $r->setWebService("shoppingCart")
            ->setService("shoppingCart")
            ->setMethod("PUT")
            ->setParameter($queryString)
            ->setDataToSend($productType)
            ->needAuthorization()
            ->setExpectedHttpStatusCode(200)
            ->send();

        //return $res->success == 'True';

        return true;
    }

    public function add($id, stdClass $data): bool
    {
        // You add with JS straight HTTP call
        return true;
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id, stdClass $data = null): bool
    {
        $data = new StdClass;
        $data->productId = "1001";
        $data->newQty = 0; // this will delete the product

        $this->update($id, $data);

        return true;
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function deleteAll($id, stdClass $data = null): bool
    {
        $queryString = "user/{$id}/deleteShoppingCart";

        $r = new Http();
        $res = $r->setWebService("shoppingCart")
            ->setService("shoppingCart")
            ->setMethod("DELETE")
            ->setParameter($queryString)
            ->needAuthorization()
            ->setExpectedHttpStatusCode(200)
            ->send();

        return true;
    }
}