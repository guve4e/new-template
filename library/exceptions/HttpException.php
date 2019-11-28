<?php
require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once (THIRD_LIB_PATH . "/kint/Kint.class.php");

/**
 * HttpException
 * Extends the Exception Class.
 */
class HttpException extends Exception
{
    private $jsonLoader;

    public function __construct($message, PhpHttpAdapter $httpInfo)
    {
        parent::__construct($message);

        try {
            $this->jsonLoader = new SiteConfigurationLoader(new FileManager());
            $siteConfig = $this->jsonLoader->getData();

            if ($siteConfig->debug) {
                Kint::dump($httpInfo);
            } else {
                $this->message = "Something went wrong! Please call customer support! Code 81221!";
            }

        } catch (Exception $e) {
            die("Couldn't open the site config file!");
        }
    }
}