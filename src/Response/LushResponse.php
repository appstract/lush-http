<?php

namespace Appstract\LushHttp\Response;

use Appstract\LushHttp\Request\LushRequest;

class LushResponse
{
    /**
     * @var mixed
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $headers;

    /**
     * @var mixed
     */
    public $content;

    /**
     * @var mixed
     */
    protected $isJson;

    /**
     * @var mixed
     */
    protected $isXml;

    /**
     * @var bool
     */
    protected $autoFormat = true;

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

        if(isset($this->request->options['auto_format'])) {
            $this->autoFormat = $this->request->options['auto_format'];
        }

        if($this->autoFormat) {
            $this->formatContent();
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
        if (isset($this->isJson)) {
            return $this->isJson;
        }

        // check based on content header
        if (strpos($this->getHeader('content_type'), 'application/json') !== false) {
            $this->isJson = true;
        } else {
            // check based on content
            json_decode($this->content);
            $this->isJson = (json_last_error() == JSON_ERROR_NONE);
        }

        return $this->isJson;
    }

    /**
     * @return bool
     */
    public function isXml()
    {
        if (isset($this->isXml)) {
            return $this->isXml;
        }

        if (strpos($this->getHeader('content_type'), 'text/xml') !== false) {
            $this->isXml = true;
        } else {
            $this->isXml = false;
        }

        return $this->isXml;
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
     * @return string
     */
    public function getStatusCode()
    {
        return $this->getHeader('http_code');
    }

    /**
     * Get the content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->getHeader('content_type');
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
    public function &__get($property)
    {
        $return = null;

        // check if the property is present in the content
        if (isset($this->content->{ $property })) {
            $return = $this->content->{ $property };
        }

        return $return;
    }

    /**
     * Auto format content
     */
    protected function formatContent()
    {
        if ($this->isXml()) {
            $this->content = simplexml_load_string($this->content);
        } else if ($this->isJson()) {
            $this->content = json_decode($this->content);
        }
    }
}
