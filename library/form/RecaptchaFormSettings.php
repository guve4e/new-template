<?php

require_once ("FormSettings.php");
require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");

class RecaptchaFormSettings implements FormSettings {

    private $secret = null;
    private $score_pivot = null;
    private $recaptchaResponse;


    /**
     * RecaptchaFormSettings constructor.
     * @param FileManager $fileManager
     * @throws Exception
     */
    public function __construct(FileManager $fileManager)
    {
        $jsonLoader = new SiteConfigurationLoader($fileManager);
        $siteConfiguration = $jsonLoader->getData();

        if (!isset($siteConfiguration->recaptcha))
            throw new Exception("Configure config file / Add recaptcha secret and score pivot!");

        $this->secret = $siteConfiguration->recaptcha->secret;
        $this->score_pivot = $siteConfiguration->recaptcha->score_pivot;
    }

    /**
     * @throws Exception
     */
    public function validate()
    {
        if (isset($_POST['g-recaptcha-response']))
            $captcha = $_POST['g-recaptcha-response'];
        else
            throw new Exception("Specify recaptcha input field in the form!");

        $response = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret="
            . $this->secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']
        );

        $this->recaptchaResponse = json_decode($response);

        if ($this->recaptchaResponse ->success === false)
            new Exception("Call to google api failed!");
    }

    /**
     * @throws Exception
     */
    public function do()
    {
        if ($this->recaptchaResponse->score < $this->score_pivot)
            throw new Exception("Bad Use of site!");
    }
}