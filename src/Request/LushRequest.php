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
        $this->method = $method;
        $this->payload = $payload;

        $this->prepareRequest();
    }

    /**
     * Prepare the request.
     */
    protected function prepareRequest()
    {
        $this->addHeaders();
        $this->addParameters();
        $this->setOptions();
    }

    /**
     * Add request headers.
     */
    protected function addHeaders()
    {
        $userHeaders = array_map(function ($key, $value) {
            // format header like this 'x-header: value'
            return sprintf('%s: %s', $key, $value);
        }, array_keys($this->payload['headers']), $this->payload['headers']);

        $headers = array_merge($this->defaultHeaders, $userHeaders);

        $this->addOption(CURLOPT_HTTPHEADER, $headers);
    }

    /**
     *  Add request parameters.
     */
    protected function addParameters()
    {
        $parameters = http_build_query($this->payload['parameters']);

        if ($this->method == 'POST') {
            $this->addOption(CURLOPT_POSTFIELDS, $parameters);
        } else {
            // append parameters in the url
            $this->payload['url'] = sprintf('%s?%s', $this->payload['url'], $parameters);
        }
    }

    /**
     * Add a option.
     *
     * @param $key
     * @param $value
     */
    protected function addOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Set request options.
     */
    protected function setOptions()
    {
        // Add user options
        if (is_array($this->payload['options'])) {
            foreach ($this->payload['options'] as $option => $value) {
                $this->addOption(OptionResolver::resolve($option), $value);
            }
        }

        if ($this->method == 'POST') {
            $this->addOption(CURLOPT_POST, true);
        } elseif (in_array($this->method, ['PUT', 'DELETE'])) {
            $this->addOption(CURLOPT_CUSTOMREQUEST, $this->method);
        }

        if (defined('CURLOPT_PROTOCOLS')) {
            $this->addOption(CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        }

        $this->mergeCurlOptions();
    }

    /**
     * Send the Curl request.
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function send()
    {
        $response = $this->makeRequest();

        return new LushResponse($response, $this);
    }
}
