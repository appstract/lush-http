<?php

namespace Appstract\LushHttp\Request;

trait RequestGetters
{
    /**
     * Get the payload.
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Get the URL.
     *
     * @return mixed|string
     */
    public function getUrl()
    {
        return isset($this->payload['url']) ? $this->payload['url'] : null;
    }

    /**
     * Get all parameters.
     *
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
     * Get all options.
     *
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
     * Get all headers.
     *
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
}
