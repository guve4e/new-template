<?php

require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once (HTTP_PATH . "/PhpHttpAdapter.php");

class Http
{
    private $config;
    private $contentType = "";
    private $configuration = null;
    private $webService;
    private $service;
    private $method;
    private $jsonData;
    private $parameter;
    private $mock;
    private $needAuthorization = false;
    private $expectedHttpStatusCode = 0;

    /**
     * @param array $servicesArray
     * @param string $serviceName
     * @return array
     * @throws Exception
     */
    private function extractConfigByServiceName($servicesArray, string $serviceName)
    {
        foreach ($servicesArray as $service)
        {
            if ($service->name == $serviceName)
                return $service;
        }

        throw new Exception("Couldn't find service with the name {$serviceName} !");
    }

    /**
     * Http constructor.
     * @throws Exception
     */
    public function __construct()
    {
        // load configuration
        $jsonLoader = new SiteConfigurationLoader(new FileManager());
        $this->config = $jsonLoader->getData();
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    public function setDataToSend($json_data)
    {
        $this->jsonData = $json_data;
        return $this;
    }

    public function setController(string $name)
    {
        $this->controllerName = $name;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws Exception
     */
    public function setWebService(string $name)
    {
        $this->webService = $name;
        $this->configuration = $this->extractConfigByServiceName($this->config->web_services, $this->webService);

        return $this;
    }

    public function setService(string $name)
    {
        $this->service = $name;
        return $this;
    }

    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function setMock()
    {
        $this->mock = true;
        return $this;
    }

    public function needAuthorization()
    {
        $this->needAuthorization = true;
        return $this;
    }

    public function setExpectedHttpStatusCode($expectedHttpStatusCode)
    {
        $this->expectedHttpStatusCode = $expectedHttpStatusCode;
        return $this;
    }

    public function setContentTypeApplicationJson()
    {
        $this->contentType = "application/json";
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send()
    {
        $rc = new PhpHttpAdapter();
        $rc->setRestCallType(new RestCall("Curl", new FileManager()))
            ->setWebServicesConfiguration($this->configuration)
            ->setWebServiceName($this->webService)
            ->setController($this->service)
            ->setAuthorization($this->needAuthorization)
            ->setMethod($this->method)
            ->setParameter($this->parameter)
            ->setContentType($this->contentType)
            ->setDataToSend($this->jsonData)
            ->setExpectedHttpStatusCode($this->expectedHttpStatusCode);

        if ($this->mock)
            $rc->setMock();

        return $rc->send();
    }
}