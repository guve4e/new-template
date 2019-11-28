<?php

class Analytics
{
    private $request;
    private $fileManager;
    private $useAnalytics;
    private $visitorInfo;

    /**
     * Analytics constructor.
     * @throws Exception
     */
    public function __construct(Http $request, FileManager $fileManager, VisitorInfo $visitorInfo)
    {
        $this->request = $request;
        $this->fileManager = $fileManager;
        $this->visitorInfo = $visitorInfo;

        $jsonLoader = new SiteConfigurationLoader($fileManager);
        $siteConfiguration = $jsonLoader->getData();
        $this->useAnalytics = $siteConfiguration->log_analytics;
    }

    /**
     * @throws Exception
     */
    public function post()
    {
        if ($this->useAnalytics)
        {
            // Convert to array
            $visitorInfo = json_decode(json_encode($this->visitorInfo), true);

            $this->request->setWebService("analytics")
                ->setService("stats")
                ->setMethod("POST")
                ->setDataToSend($visitorInfo)
                ->needAuthorization()
                ->setExpectedHttpStatusCode(200)
                ->send();
        }
    }
}