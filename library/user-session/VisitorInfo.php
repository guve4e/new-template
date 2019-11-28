<?php

class VisitorInfo implements JsonSerializable
{
    private $ipAddress;
    private $page;
    private $referrer;
    private $requestMethod;
    private $remoteHost;
    private $userAgent;
    private $sessionId;

    /**
     * VisitorInfo constructor.
     */
    public function __construct()
    {
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];

        $this->page = "{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        $this->page .= isset($_SERVER['QUERY_STRING']) ? $_SERVER["QUERY_STRING"] : "No query string";

        $this->referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER["HTTP_REFERER"] : "No Referer";
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->remoteHost = @getHostByAddr($this->ipAddress);

        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->sessionId = session_id();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'ipAddress' => $this->ipAddress,
            'page' => $this->page,
            'referrer' => $this->referrer,
            'requestMethod' => $this->requestMethod,
            'remoteHost' => $this->remoteHost,
            'userAgent' => $this->userAgent,
            'sessionId' => $this->sessionId
        ];
    }
}