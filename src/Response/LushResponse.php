<?php

namespace Appstract\LushHttp\Response;

use Appstract\LushHttp\Request\LushRequest;
use Appstract\LushHttp\Events\ResponseEvent;

class LushResponse
{
    use ResponseGetters;

    public $content;

    protected $request;

    protected $headers;

    protected $isJson;

    protected $isXml;

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

        if (isset($this->request->options['auto_format'])) {
            $this->autoFormat = $this->request->options['auto_format'];
        }

        if (function_exists('event')) {
            event(new ResponseEvent($this));
        }

        if ($this->autoFormat) {
            $this->formatContent();
        }
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
     * Auto format content.
     */
    protected function formatContent()
    {
        if ($this->request->method == 'HEAD') {
            $this->content = (object) $this->headers;
        } elseif ($this->isXml()) {
            $this->content = simplexml_load_string($this->content);
        } elseif ($this->isJson()) {
            $this->content = json_decode($this->content);
        }
    }
}
