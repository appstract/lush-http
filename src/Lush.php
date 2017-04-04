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
    public function __construct($baseUrl = '', array $options = [])
    {
        // without curl, we can do anything
        if (! extension_loaded('curl') || ! function_exists('curl_init')) {
            throw new LushException('cUrl is not enabled on this server');
        }

        // append trailing slash to the
        // baseUrl if it is missing
        if (! empty($baseUrl) && substr($baseUrl, -1) !== '/') {
            $baseUrl = $baseUrl.'/';
        }

        $this->baseUrl = $baseUrl;
        $this->options = $options;
    }

    /**
     * Magic shorthand method.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function __call($method, array $arguments)
    {
        $url = isset($arguments[0]) ? $arguments[0] : '';
        $parameters = isset($arguments[1]) ? $arguments[1] : [];
        $headers = isset($arguments[2]) ? $arguments[2] : [];
        $options = isset($arguments[3]) ? $arguments[3] : [];

        return $this->request($method, $url, $parameters, $headers, $options);
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
    public function request($method, $url = '', $parameters = '', array $headers = [], array $options = [])
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

        $request = new LushRequest($this->method, [
            'url' => $this->url,
            'parameters' => $this->parameters,
            'headers' => $this->headers,
            'options' => $this->options
        ]);

        return $request->send();
    }
}
