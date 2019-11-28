<?php

require_once (LIBRARY_PATH . "/dao/IDO.php");
require_once (HTTP_PATH . "/Http.php");

class Contact implements IDO
{
    /**
     * Get resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function get($id)
    {
        return true;
    }

    /**
     * Create resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function create($id, stdClass $data = null): bool
    {
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: '. 'AVA Cosmetika Customer' .'<'. 'order@avacosmetika.com' .'>' . "\r\n";

        $htmlContent = " 
            <html> 
            <head> 
                <title>Customer message</title> 
            </head> 
            <body> 
                <h1>Ticket</h1> 
                <table cellspacing='0' style='border: 2px dashed #FB4314; width: 100%;'> 
                    <tr> 
                        <th>Name:</th><td>{$data->name}</td> 
                    </tr> 
                    <tr style='background-color: #e0e0e0;'> 
                        <th>Email:</th><td>{$data->email}</td> 
                    </tr> 
                    <tr> 
                        <th>Phone Number:</th><td>{$data->phonenumber}</td> 
                    </tr> 
                </table> 
                <br>
                <p>{$data->message}</p>
            </body> 
            </html>";

        if(mail('info@avacosmetika.com',"Customer message", $htmlContent, $headers))
        {

        }
        else
        {
            $errorMessage = error_get_last()['message'];
            throw new Exception("Something went wrong! Please contact us at info@avacosmetika.com");
        }

        return true;
    }

    /**
     * Update Resource.
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update($id, stdClass $data = null): bool
    {
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
     * @throws Exception
     */
    public function delete($id, stdClass $data = null): bool
    {
        return true;
    }
}