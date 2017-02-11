<?php

namespace Appstract\LushHttp;

use Appstract\LushHttp\Request\LushRequest;

class Lush
{

    /**
     * @var
     */
    public $method;

    /**
     * @var string
     */
    public $baseUrl = '';

    /**
     * @var
     */
    public $url;

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
     */
    public function __construct($baseUrl = '')
    {
        // append trailing slash if it is missing
        if (!empty($baseUrl) && substr($baseUrl, -1) !== '/') {
            $baseUrl = $baseUrl.'/';
        }

        $this->baseUrl = $baseUrl;
    }

    /**
     * get shorthand
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function get($url = '', $parameters = [], $headers = [])
    {
        return $this->request('GET', $url, $parameters, $headers);
    }

    /**
     * post shorthand
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function post($url = '', $parameters = [], $headers = [])
    {
        return $this->request('POST', $url, $parameters, $headers);
    }

    /**
     * put shorthand
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function put($url = '', $parameters = [], $headers = [])
    {
        return $this->request('PUT', $url, $parameters, $headers);
    }

    /**
     * delete shorthand
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function delete($url = '', $parameters = [], $headers = [])
    {
        return $this->request('DELETE', $url, $parameters, $headers);
    }

    /**
     * Create a request
     *
     * @param        $method
     * @param        $url
     * @param string $parameters
     * @param array  $headers
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function request($method, $url = '', $parameters = '', $headers = [])
    {
        $this->method       = $method;
        $this->url          = $url;
        $this->parameters   = $parameters;
        $this->headers      = $headers;

        return $this->createRequest();
    }

    /**
     * transforms data to send function
     *
     * @throws \Exception
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    protected function createRequest()
    {
        $this->method   = strtoupper($this->method);
        $this->url      = trim($this->baseUrl.$this->url);

        if(empty($this->url)) {
            throw new \Exception ('URL is empty');
        }

        return $this->send($this->method, $this->url, $this->parameters, $this->headers);
    }

    /**
     * Create the Lush request and send it
     *
     * @param        $method
     * @param        $url
     * @param string $parameters
     * @param array  $headers
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    protected function send($method, $url, $parameters = '', $headers = [])
    {
        $request = new LushRequest($method, compact('url', 'parameters', 'headers'));
        return $request->send();
    }
}