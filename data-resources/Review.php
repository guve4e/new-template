<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (HTTP_PATH . "/Http.php");

class Review implements IDO
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
        $r->setWebService("review")
            ->setService("review/reviewsByProductId/1001")
            ->setMethod("GET")
            ->needAuthorization()
            ->setExpectedHttpStatusCode(200);
        $products = $r->send();

        return $products;
    }

    /**
     * Create resource.
     * @param int $id
     * @return mixed
     */
    public function create($id, stdClass $data = null): bool
    {
        // TODO: Implement create() method.
    }

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     */
    public function update($id, stdClass $data = null): bool
    {
        return true;
    }

    public function add($id, stdClass $data): bool
    {
//        $r = new Http();
//        $r->setWebService("product")
//            ->setService("products/1001")
//            ->setMethod("GET")
//            ->needAuthorization()
//            ->setExpectedHttpStatusCode(200);
//        $products = $r->send();
//
//        return $products;

        $date = new DateTime();

        $review = new StdClass;
        $review->stars = $data->rate;
        $review->productId = 1001;
        $review->createdBy = "John Doe";
        $review->comment = $data->comment;
        $review->picturePath = "somePicture.jpg";


        $r = new Http();
        $r->setWebService("review")
            ->setService("review")
            ->setMethod("POST")
            ->setDataToSend($review)
            ->needAuthorization()
            ->setExpectedHttpStatusCode(201)
            ->send();

        return true;
    }

    /**
     * Delete Resource.
     * @param int $id
     * @return mixed
     */
    public function delete($id, stdClass $data = null): bool
    {
        // TODO: Implement delete() method.
    }
}