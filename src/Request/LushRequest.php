<?php

namespace Appstract\LushHttp\Request;

class LushRequest extends CurlRequest
{
    /**
     * LushRequest constructor.
     *
     * @param string $method
     * @param array $payload
     */
    public function __construct($method, array $payload)
    {
        parent::__construct();

        $this->method = $method;
        $this->payload = $payload;

        $this->prepareRequest();
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return mixed|string
     */
    public function getUrl()
    {
        return isset($this->payload['url']) ? $this->payload['url'] : '';
    }

    /**
     * @return array|mixed
     */
    public function getParameters()
    {
        return isset($this->payload['parameters']) ? $this->payload['parameters'] : [];
    }

    /**
     * Get a specific parameter.
     *
     * @param $parameter
     *
     * @return mixed
     */
    public function getParameter($parameter)
    {
        return isset($this->getParameters()[$parameter]) ? $this->getParameters()[$parameter] : null;
    }

    /**
     * @return array|mixed
     */
    public function getOptions()
    {
        return isset($this->payload['options']) ? $this->payload['options'] : [];
    }

    /**
     * Get a specific option.
     *
     * @param $option
     *
     * @return mixed
     */
    public function getOption($option)
    {
        return isset($this->getOptions()[$option]) ? $this->getOptions()[$option] : null;
    }

    /**
     * @return array|mixed
     */
    public function getHeaders()
    {
        return isset($this->payload['headers']) ? $this->payload['headers'] : [];
    }

    /**
     * Get a specific header.
     *
     * @param $header
     *
     * @return mixed
     */
    public function getHeader($header)
    {
        return isset($this->getHeaders()[$header]) ? $this->getHeaders()[$header] : null;
    }

    /**
     * Prepare the request.
     */
    protected function prepareRequest()
    {
        $this->addHeaders();
        $this->addParameters();
        $this->initOptions();
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

        $this->addCurlOption(CURLOPT_HTTPHEADER, $headers);
    }

    /**
     *  Add request parameters.
     */
    protected function addParameters()
    {
        $parameters = http_build_query($this->payload['parameters']);

        if (in_array($this->method, ['DELETE', 'PATCH', 'POST', 'PUT'])) {
            $this->addCurlOption(CURLOPT_POSTFIELDS, $parameters);
        } else {
            // append parameters in the url
            $this->payload['url'] = sprintf('%s?%s', $this->payload['url'], $parameters);
        }
    }

    /**
     * Add Lush option.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function addOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Add Curl option.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function addCurlOption($key, $value)
    {
        $this->curlOptions[$key] = $value;
    }

    /**
     * Set request options.
     */
    protected function initOptions()
    {
        // Set method
        if ($this->method == 'POST') {
            $this->addCurlOption(CURLOPT_POST, true);
        } elseif (in_array($this->method, ['DELETE', 'HEAD', 'PATCH', 'PUT'])) {
            if ($this->method == 'HEAD') {
                $this->addCurlOption(CURLOPT_NOBODY, true);
            }

            $this->addCurlOption(CURLOPT_CUSTOMREQUEST, $this->method);
        }

        // Set allowed protocols
        if (defined('CURLOPT_PROTOCOLS')) {
            $this->addCurlOption(CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        }

        // Handle options from payload
        if (is_array($this->payload['options'])) {
            // Add authentication
            $this->handleAuthentication();

            // Add user options
            $this->handleUserOptions();
        }

        $this->mergeCurlOptions();
    }

    /**
     *  Handle authentication.
     */
    protected function handleAuthentication()
    {
        if (isset($this->payload['options']['username'], $this->payload['options']['password'])) {
            $this->addCurlOption(CURLOPT_USERPWD, sprintf('%s:%s', $this->payload['options']['username'], $this->payload['options']['password']));
        }
    }

    /**
     *  Handle user options.
     */
    protected function handleUserOptions()
    {
        foreach ($this->payload['options'] as $option => $value) {
            $resolvedOption = OptionResolver::resolve($option);

            if ($resolvedOption['type'] == 'curl_option') {
                $this->addCurlOption($resolvedOption['option'], $value);
            } else {
                $this->addOption($option, $value);
            }
        }
    }

    /**
     * Send the Curl request.
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function send()
    {
        return $this->makeRequest();
    }
}
