<?php

namespace Appstract\LushHttp\Response;

use Appstract\LushHttp\Request\LushRequest;
use Appstract\LushHttp\Events\ResponseEvent;

class LushResponse
{
    use ResponseGetters;

    public $content;

    public $object;

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
            $this->object = $this->formatContent($this->content);
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
     * format content.
     *
     * @param $content
     *
     * @return mixed
     */
    protected function formatContent($content)
    {
        if ($this->request->method == 'HEAD') {
            return json_decode($this->headers);
        }

        if ($this->isXml()) {
            return json_decode($this->parseXml($content));
        }

        if ($this->isJson()) {
            return json_decode($content);
        }
    }

    /**
     * Parse xml to array.
     *
     * @param $xml
     *
     * @return mixed
     */
    protected function parseXml($xml)
    {
        return json_encode(
            simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)
        );
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

        // check if the property is present in the content object
        if (isset($this->object->{ $property })) {
            $return = $this->object->{ $property };
        }

        return $return;
    }

    /**
     * Proxy function calls to the collection.
     *
     * @param       $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        return call_user_func_array([$this->getCollection(), $method], $arguments);
    }
}
