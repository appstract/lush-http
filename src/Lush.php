<?php

namespace Appstract\Lush;

class Lush
{

    /**
     * @var
     */
    public $method;

    /**
     * @var string
     */
    public $protocol;

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var
     */
    public $path;

    /**
     * @var array
     */
    public $parameters = [];

    /**
     * @var array
     */
    public $headers = [];

    /**
     * Lush constructor.
     *
     * @param string $baseUrl
     * @param string $protocol
     */
    public function __construct($baseUrl = 'localhost', $protocol = 'http')
    {
        $this->baseUrl = $baseUrl;
        $this->protocol = $protocol;
    }

    /**
     * get shorthand
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @return LushResponse
     */
    public function get($path = '', $parameters = [], $headers = [])
    {
        $this->method       = 'GET';
        $this->path         = $path;
        $this->parameters   = $parameters;
        $this->headers      = $headers;

        return $this->createRequest();
    }

    /**
     * post shorthand
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @return LushResponse
     */
    public function post($path = '', $parameters = [], $headers = [])
    {
        $this->method       = 'POST';
        $this->path         = $path;
        $this->parameters   = $parameters;
        $this->headers      = $headers;

        return $this->createRequest();
    }

    /**
     * put shorthand
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @return LushResponse
     */
    public function put($path = '', $parameters = [], $headers = [])
    {
        $this->method       = 'PUT';
        $this->path         = $path;
        $this->parameters   = $parameters;
        $this->headers      = $headers;

        return $this->createRequest();
    }

    /**
     * delete shorthand
     *
     * @param string $path
     * @param array  $parameters
     * @param array  $headers
     *
     * @return LushResponse
     */
    public function delete($path = '', $parameters = [], $headers = [])
    {
        $this->method       = 'DELETE';
        $this->path         = $path;
        $this->parameters   = $parameters;
        $this->headers      = $headers;

        return $this->createRequest();
    }

    /**
     * transforms data to request function
     *
     * @return LushResponse
     */
    protected function createRequest()
    {
        $url = $this->protocol.'://'.$this->baseUrl.'/'.$this->path;
        return $this->request($this->method, $url, $this->parameters, $this->headers);
    }

    /**
     * Creates the request
     *
     * @param        $method
     * @param        $url
     * @param string $parameters
     * @param array  $headers
     *
     * @return LushResponse
     */
    public function request($method, $url, $parameters = '', $headers = [])
    {
        $request = new LushRequest($method, compact('url', 'parameters', 'headers'));

        return $request->send();
    }
}