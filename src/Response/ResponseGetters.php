<?php

namespace Appstract\LushHttp\Response;

use Illuminate\Support\Collection;

trait ResponseGetters
{
    /**
     * Get the content of the result.
     *
     * @return mixed
     */
    public function getResult()
    {
        if ($this->autoFormat && ! empty($this->object)) {
            return $this->object;
        }

        return $this->content;
    }

    /**
     * Get the content of the result.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Content as object.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Content as Collection.
     *
     * @return Collection
     */
    public function getCollection()
    {
        return new Collection($this->object);
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
}
