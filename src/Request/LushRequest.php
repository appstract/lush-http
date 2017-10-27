<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Events\RequestEvent;
use Appstract\LushHttp\Exception\LushException;

class LushRequest extends CurlRequest
{
    use RequestGetters;

    protected $allowedMethods = [
        'DELETE',
        'GET',
        'HEAD',
        'PATCH',
        'POST',
        'PUT',
    ];

    /**
     * LushRequest constructor.
     *
     * @param array  $payload
     */
    public function __construct(array $payload)
    {
        parent::__construct();

        $this->payload = $payload;
        $this->method = $payload['method'];

        $this->prepareRequest();
    }

    /**
     * Prepare the request.
     */
    protected function prepareRequest()
    {
        $this->formatUrl();
        $this->validateInput();
        $this->addHeaders();
        $this->addRequestBody();
        $this->initOptions();
    }

    /**
     * Format url.
     */
    protected function formatUrl()
    {
        // append trailing slash to the
        // baseUrl if it is missing
        if (! empty($this->payload['base_url']) && substr($this->payload['base_url'], -1) !== '/') {
            $this->payload['base_url'] = $this->payload['base_url'].'/';
        }

        // append the base url
        $this->payload['url'] = trim($this->payload['base_url'].$this->payload['url']);
    }

    /**
     * Validate given options.
     */
    protected function validateInput()
    {
        if (! filter_var($this->payload['url'], FILTER_VALIDATE_URL)) {
            throw new LushException('URL is invalid', 100);
        }

        if (! in_array($this->method, $this->allowedMethods)) {
            throw new LushException(sprintf("Method '%s' is not supported", $this->method), 101);
        }
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
     *  Add request body.
     */
    protected function addRequestBody()
    {
        if (! empty($this->payload['parameters'])) {
            if (in_array($this->method, ['DELETE', 'PATCH', 'POST', 'PUT'])) {
                $this->addCurlOption(CURLOPT_POSTFIELDS, $this->formattedRequestBody());
            } elseif (is_array($this->payload['parameters'])) {
                // append parameters in the url
                $this->payload['url'] = sprintf('%s?%s', $this->payload['url'], $this->formattedRequestBody());
            }
        }
    }

    /**
     * Get formatted request body based on body_format.
     *
     * @return null|string
     */
    protected function formattedRequestBody()
    {
        if (isset($this->payload['options']['body_format']) && $this->payload['options']['body_format'] == 'json') {
            return json_encode($this->payload['parameters']);
        }

        return http_build_query($this->payload['parameters']);
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
        if (! empty($this->payload['options']) && is_array($this->payload['options'])) {
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
            $resolvedOption = RequestOptions::resolve($option);

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
        if (function_exists('event')) {
            event(new RequestEvent($this));
        }

        return $this->makeRequest();
    }
}
