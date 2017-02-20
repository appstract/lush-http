<?php

namespace Appstract\LushHttp\Response;

use Appstract\LushHttp\Request\LushRequest;

class LushResponse
{
    /**
     * @var null
     */
    protected $request;
    /**
     * @var
     */
    protected $headers;

    /**
     * @var mixed
     */
    public $content;

    /**
     * @var mixed
     */
    public $isJson;

    /**
     * LushResponse constructor.
     *
     * @param      $response
     * @param LushRequest $request
     */
    public function __construct($response, LushRequest $request)
    {
        $this->request = $request;
        $this->headers = $response['headers'];
        $this->content = $response['content'];

        if ($this->isJson()) {
            $this->content = json_decode($this->content);
        }
    }

    /**
     * Get the content of the result.
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->content;
    }

    /**
     * Check if content is json.
     *
     * @return null
     */
    public function isJson()
    {
        if (! isset($this->isJson)) {
            json_decode($this->content);
            $this->isJson = (json_last_error() == JSON_ERROR_NONE);
        }

        return $this->isJson;
    }

    /**
     * Get the headers.
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
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
        return isset($this->headers[$header]) ? $this->headers[$header] : null;
    }

    /**
     * Get the status code.
     *
     * @return null
     */
    public function getStatusCode()
    {
        return $this->getHeader('http_code');
    }

    /**
     * Get the original request.
     *
     * @return \Appstract\LushHttp\Request\LushRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Magic getter for content properties.
     *
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        // check if the property is present in the content
        if (isset($this->content->{ $property })) {
            return $this->content->{ $property };
        }
    }
}
