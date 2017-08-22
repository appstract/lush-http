<?php

namespace Appstract\LushHttp;

use Appstract\LushHttp\Request\LushRequest;
use Appstract\LushHttp\Exception\LushException;

class Lush
{
    public $baseload;

    public $url;

    public $parameters = [];

    public $headers = [];

    public $options = [];

    /**
     * Lush constructor.
     *
     * @param string $baseUrl
     * @param array  $options
     * @param array  $headers
     */
    public function __construct($baseUrl = '', array $options = [], array $headers = [])
    {
        // without curl, we can't do anything
        if (! extension_loaded('curl') || ! function_exists('curl_init')) {
            throw new LushException('cUrl is not enabled on this server');
        }

        $this->baseload = [
            'base_url' => trim($baseUrl),
            'options' => $options,
            'headers' => $headers,
        ];
    }

    /**
     * Set the url with parameters.
     *
     * @param            $url
     * @param array|null $parameters
     *
     * @return $this
     */
    public function url($url, array $parameters = [])
    {
        $this->url = $url;
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Set headers.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function headers(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Create a request.
     *
     * @param $method
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function request($method)
    {
        $request = new LushRequest([
            'method' => strtoupper($method),
            'base_url' => $this->baseload['base_url'],
            'url' => trim($this->url),
            'parameters' => $this->parameters,
            'headers' => array_merge($this->baseload['headers'], $this->headers),
            'options' => array_merge($this->baseload['options'], $this->options),
        ]);

        return $request->send();
    }

    /**
     * Magic shorthand method.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function __call($method, array $arguments = [])
    {
        $scope = $this;

        if (isset($arguments[0])) {
            $scope = $this->url($arguments[0], isset($arguments[1]) ? $arguments[1] : []);
        }

        return $scope->request($method);
    }
}
