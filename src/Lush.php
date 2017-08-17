<?php

namespace Appstract\LushHttp;

use Appstract\LushHttp\Request\LushRequest;
use Appstract\LushHttp\Exception\LushException;

class Lush
{
    public $baseload;

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
            'headers' => $headers
        ];
    }

    /**
     * Create a request.
     *
     * @param        $method
     * @param        $url
     * @param array $parameters
     * @param array  $headers
     * @param array  $options
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function request($method, $url = '', array $parameters = [], array $headers = [], array $options = [])
    {
        $request = new LushRequest([
            'method' => strtoupper($method),
            'base_url' => $this->baseload['base_url'],
            'url' => trim($url),
            'parameters' => $parameters,
            'headers' => array_merge($this->baseload['headers'], $headers),
            'options' => array_merge($this->baseload['options'], $options)
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
    public function __call($method, array $arguments)
    {
        return $this->request($method,
            $this->parseArgument($arguments, 0, ''), // url
            $this->parseArgument($arguments, 1), // parameters
            $this->parseArgument($arguments, 2), // headers
            $this->parseArgument($arguments, 3) // options
        );
    }

    /**
     * @param       $arguments
     * @param       $key
     * @param array $default
     *
     * @return array
     */
    protected function parseArgument($arguments, $key, $default = [])
    {
        return isset($arguments[$key]) ? $arguments[$key] : $default;
    }
}