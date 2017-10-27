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
        // We need cUrl to work
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
     * @param array|object $parameters
     *
     * @return $this
     */
    public function url($url, $parameters = [])
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
     * Reset all request options.
     *
     * @return $this
     */
    public function reset()
    {
        $this->url = '';
        $this->parameters = [];
        $this->headers = [];
        $this->options = [];

        return $this;
    }

    /**
     * Post as Json.
     *
     * @return $this
     */
    public function asJson()
    {
        $this->addOption('body_format', 'json');
        $this->addHeader('content_type', 'application/json');

        return $this;
    }

    /**
     * Post as form params.
     *
     * @return $this
     */
    public function asFormParams()
    {
        $this->addHeader('content_type', 'application/x-www-form-urlencoded');

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

    /**
     * Add header.
     *
     * @param $name
     * @param $value
     */
    protected function addHeader($name, $value)
    {
        $this->baseload['headers'] = array_merge($this->baseload['headers'], [$name => $value]);
    }

    /**
     * Add option.
     *
     * @param $name
     * @param $value
     */
    protected function addOption($name, $value)
    {
        $this->baseload['options'] = array_merge($this->baseload['options'], [$name => $value]);
    }
}
