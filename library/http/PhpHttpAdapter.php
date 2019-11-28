<?php

require_once ("phphttp/RestCall.php");
require_once ( LIBRARY_PATH . "/exceptions/HttpException.php");
require_once (UTILITY_PATH . "/Logger.php");

/**
 * Wrapper around RestCall.
 * TODO Performance! It always make 2 calls (auth + webapi)
 */

class PhpHttpAdapter {

    private $url = null;
    private $method;
    private $controllerName;
    private $parameter;
    private $isMock = false;
    private $jsonData = null;
    private $contentType = "";
    private $success;
    private $restCall;
    private $webService = [];
    private $webServicesConfiguration = [];
    private $needAuthorization = false;
    private $expectedHttpStatusCode = 0;

    /**
     * Constructs Mock URL
     * @return string as a path
     * @throws Exception
     */
    private function mockServiceUrl() : string
    {
        $path = $this->webService['url_base_local'];
        $path .= "/" . $this->controllerName . ".json";

        return $path;
    }

    /**
     * Figures out the content type.
     * @param $method
     * @throws Exception
     */
    private function configureContentType(string $method)
    {
        if(!isset($method)) throw new Exception("Method not set!");

        // If content type is not specified
        if ($this->contentType == "")
        {
            if($method == "GET")
                $this->contentType = "application/x-www-form-urlencoded";
            else
                $this->contentType = "application/json";
        }
    }

    /**
     * Constructs the URL used for RestCall
     * @throws Exception
     */
    private function constructUrl()
    {
        if (!$this->isMock) {
            $base = $this->webService['url_base_remote'];
            $url = $base . "/" . $this->controllerName;

            if(isset($this->parameter))
                $url .= "/" . $this->parameter;

            $this->url = $url;
        } else {
            $this->url = $this->mockServiceUrl();
        }
    }

    /**
     * Determines Success
     * @param RestResponse $res
     * @throws Exception
     */
    private function determineSuccess(RestResponse $res)
    {
        if ($res->getHttpCode() == $this->expectedHttpStatusCode)
            $this->success = true;
        else
            throw new HttpException("Unexpected http code, expected {$this->expectedHttpStatusCode}
            , but received {$res->getHttpCode()}", $this);
    }

    /**
     * Sends the Request.
     *
     * Wrapper aground RestRequest::http_send
     * @return RestResponse
     * @throws Exception
     */
    private function httpSend() : RestResponse
    {
        $headers = $this->setSecurityHeaders();

        $this->restCall->setUrl($this->url)
            ->setContentType($this->contentType)
            ->setMethod($this->method);

        // If client did specify body
        if (!is_null($this->jsonData))
            $this->restCall->addBody((array) $this->jsonData);

        $this->restCall->setHeaders($headers);
        $this->restCall->send();

        $request = $this->restCall->getResponseWithInfo();

        $this->determineSuccess($request);

        return $request;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function setSecurityHeaders(): array
    {
        $headers = [
            "ApiToken" => $this->webService['api_token']
        ];

        if ($this->needAuthorization) {
            $authToken = $this->getAuthorizationToken();
            $headers['Authorization'] = "Bearer " . $authToken;
        }
        return $headers;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function getAuthorizationToken()
    {
        $authConfig = $this->webServicesConfiguration['authorization'];

        $url = $authConfig['url'];
        $username = $authConfig['username'];
        $password = $authConfig['password'];

        $restCall = new RestCall("Curl", new FileManager());
        $restCall->setUrl($url . "?grant_type=client_credentials")
            ->setContentType("application/x-www-form-urlencoded")
            ->setMethod("POST");

        $restCall->setHeaders( [ "Authorization" => "Basic " . $this->getBarer($username, $password)]);
        $restCall->send();

        $response = $restCall->getResponseWithInfo();
        $responseInfo = $response->getInfo();

       // return "349f68ca-bf44-4b4d-8848-113c82b2e661";
        if ($responseInfo['code'] == 200)
        {
            $ff = $restCall->getResponseAsJson();
            return $ff->access_token;
        }

        throw new Exception("Bad Call to Authorization server!");
    }

    private function getBarer(string $username, string $password): string
    {
        return base64_encode($username . ":" . $password);
    }

    /**
     * @param array $arr
     * @param $key
     * @param $value
     * @return array|bool|mixed
     * @throws Exception
     */
    private function searchInMultidimensionalArray(array $arr, $key, $value)
    {
       // base case
        if (array_key_exists($key, $arr))
        {
            if ($arr[$key] == $value)
                return $arr;
        }
        foreach($arr as $element)
        {
            if (is_array($element))
            {
                if ($this->searchInMultidimensionalArray($element, $key, $value))
                    return $element;
            }
        }
        // if not found return false
        return false;
    }

    /**
     * @param RestCall $restCall
     * @return PhpHttpAdapter
     * @throws Exception
     */
    public function setRestCallType(RestCall $restCall)
    {
        if(!isset($restCall))
            throw new Exception("Bad parameter in setRestCallType!");

        $this->restCall = $restCall;
        return $this;
    }

    /**
     * @param StdClass $configuration
     * @return PhpHttpAdapter
     * @throws Exception
     */
    public function setWebServicesConfiguration(StdClass $configuration)
    {
        if(!isset($configuration))
            throw new Exception("Bad parameter in setWebServicesConfiguration!");

        $this->webServicesConfiguration = json_decode(json_encode($configuration), true);
        return $this;
    }

    public function setMock()
    {
        $this->isMock = true;
        return $this;
    }

    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * Sets Web Service Name
     * @param string $name
     * @return $this
     * @throws Exception
     */
    public function setWebServiceName(string $name)
    {
        // first check if configuration is set up
        if (empty($this->webServicesConfiguration))
            throw new Exception("You must specify a configuration first!");

        // search fot web service with the provided name
        $arr = $this->searchInMultidimensionalArray($this->webServicesConfiguration, "name" ,$name);

        if (!$arr)
            throw new Exception("There is no such web service in the configuration file!");

        // if found assign it to property
        $this->webService = $arr;
        return $this;
    }

    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function setAuthorization($needAuthorization)
    {
        $this->needAuthorization = $needAuthorization;
        return $this;
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

    public function setExpectedHttpStatusCode($expectedHttpStatusCode)
    {
        $this->expectedHttpStatusCode = $expectedHttpStatusCode;
        return $this;
    }

    /**
     * @return object
     * @throws Exception
     */
    public function send()
    {
        if ($this->expectedHttpStatusCode == 0)
            throw new Exception("Specify an expected http status code when using phphttp!");

        $this->configureContentType($this->method);
        $this->constructUrl();
        $response = $this->httpSend();

        if ($this->success)
            return $response->getBodyAsJson();
        else
            throw new HttpException("Unsuccessful API Call!", $this);
    }
}
