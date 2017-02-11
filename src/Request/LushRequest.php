<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Response\LushResponse;

class LushRequest extends CurlRequest
{

    /**
     * LushRequest constructor.
     *
     * @param $method
     * @param $payload
     */
    public function __construct($method, $payload)
    {
        $this->method   = $method;
        $this->payload  = $payload;

        $this->prepareRequest();
    }

    /**
     * Prepare the request
     */
    protected function prepareRequest()
    {
        $this->addHeaders();
        $this->addParameters();
        $this->setOptions();
    }

    /**
     * Add request headers
     */
    protected function addHeaders()
    {
        //
    }

    /**
     *  Add request parameters
     */
    protected function addParameters()
    {
        $parameters = http_build_query($this->payload['parameters']);

        if ($this->method == 'GET') {
            $this->payload['url'] = sprintf('%s?%s', $this->payload['url'], $parameters);
        } else {
            $this->addOption(CURLOPT_POSTFIELDS, $parameters);
        }
    }

    protected function addOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Set request options
     */
    protected function setOptions()
    {
        // foreach this->payload['options'], add option

        if($this->method == 'POST') {
            $this->addOption(CURLOPT_POST, true);
        }

        $this->mergeCurlOptions();
    }

    /**
     * Send the request
     *
     * @return LushResponse
     */
    public function send()
    {
        $response = $this->makeRequest();
        return new LushResponse($response, $this);
    }

}