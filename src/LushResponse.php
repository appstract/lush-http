<?php

namespace Appstract\LushHttp;


class LushResponse
{
    /**
     * @var null
     */
    protected $request;
    /**
     * @var
     */
    protected $response;

    /**
     * LushResponse constructor.
     *
     * @param      $response
     * @param null $request
     */
    public function __construct($response, $request = null)
    {
        $this->response     = $response;
        $this->request      = $request;
    }

    /**
     * Check if content is json
     *
     * @return null
     */
    public function isJson()
    {
        json_decode($this->response['content']);
        return json_last_error() == JSON_ERROR_NONE;
    }

    /**
     * Get the content of the result
     *
     * @return mixed
     */
    public function getResult()
    {
        if($this->isJson()) {
            return json_decode($this->response['content']);
        }

        return $this->response['content'];
    }

    /**
     * Get the headers
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->response['headers'];
    }

    /**
     * Get a specific header
     *
     * @param $header
     *
     * @return mixed
     */
    public function getHeader($header)
    {
        return isset($this->response['headers'][$header]) ? $this->response['headers'][$header] : null;
    }

    /**
     * Get the status code
     *
     * @return null
     */
    public function getStatusCode()
    {
        return $this->getHeader('http_code');
    }

    /**
     * Request contains curl errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return $this->response['errors']['code'] != 0;
    }

    /**
     * Get curl errors
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->response['errors'];
    }

    /**
     * Get the original request
     *
     * @return null
     */
    public function getRequest()
    {
        return $this->request;
    }
}