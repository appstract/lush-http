<?php

namespace Appstract\LushHttp;

use Appstract\LushHttp\Request\LushRequest;
use Appstract\LushHttp\Exception\LushException;

class Lush
{
    /**
     * @var
     */
    protected $allowedMethods = [
        'DELETE',
        'GET',
        'PATCH',
        'POST',
        'PUT',
    ];

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
     * @var array
     */
    public $options = [];

    /**
     * Lush constructor.
     *
     * @param string $baseUrl
     * @param array  $options
     */
    public function __construct($baseUrl = '', $options = [])
    {
        // without curl, we can do anything
        if (! extension_loaded('curl') || ! function_exists('curl_init')) {
            throw new LushException('cUrl is not enabled on this server');
        }

        // append trailing slash if it is missing
        if (! empty($baseUrl) && substr($baseUrl, -1) !== '/') {
            $baseUrl = $baseUrl.'/';
        }

        $this->baseUrl = $baseUrl;
        $this->options = $options;
    }

    /**
     * get shorthand.
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     * @param array  $options
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function get($url = '', $parameters = [], $headers = [], $options = [])
    {
        return $this->request('GET', $url, $parameters, $headers, $options);
    }

    /**
     * post shorthand.
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     * @param array  $options
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function post($url = '', $parameters = [], $headers = [], $options = [])
    {
        return $this->request('POST', $url, $parameters, $headers, $options);
    }

    /**
     * put shorthand.
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     * @param array  $options
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function put($url = '', $parameters = [], $headers = [], $options = [])
    {
        return $this->request('PUT', $url, $parameters, $headers, $options);
    }

    /**
     * delete shorthand.
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $headers
     * @param array  $options
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function delete($url = '', $parameters = [], $headers = [], $options = [])
    {
        return $this->request('DELETE', $url, $parameters, $headers, $options);
    }

    /**
     * Create a request.
     *
     * @param        $method
     * @param        $url
     * @param string $parameters
     * @param array  $headers
     * @param array  $options
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function request($method, $url = '', $parameters = '', $headers = [], $options = [])
    {
        $this->method = $method;
        $this->url = $url;
        $this->parameters = $parameters;
        $this->headers = $headers;
        $this->options = array_merge($this->options, $options);

        return $this->createRequest();
    }

    /**
     * transforms data to send function.
     *
     * @throws \Exception
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    protected function createRequest()
    {
        $this->method = strtoupper($this->method);
        $this->url = trim($this->baseUrl.$this->url);

        if (empty($this->url)) {
            throw new LushException('URL is empty', 100);
        }

        if (! in_array($this->method, $this->allowedMethods)) {
            throw new LushException(sprintf("Method '%s' is not allowed", $this->method), 101);
        }

        return $this->send($this->method, $this->url, $this->parameters, $this->headers, $this->options);
    }

    /**
     * Create the Lush request and send it.
     *
     * @param        $method
     * @param        $url
     * @param string $parameters
     * @param array  $headers
     * @param array  $options
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    protected function send($method, $url, $parameters = '', $headers = [], $options = [])
    {
        $request = new LushRequest($method, compact('url', 'parameters', 'headers', 'options'));

        return $request->send();
    }
}
