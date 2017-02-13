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
    protected $response;

    /**
     * LushResponse constructor.
     *
     * @param      $response
     * @param null $request
     */
    public function __construct($response, LushRequest $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * Get the content of the result.
     *
     * @return mixed
     */
    public function getResult()
    {
        return new ResponseContent($this->response['content']);
    }

    /**
     * Get the headers.
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->response['headers'];
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
        return isset($this->response['headers'][$header]) ? $this->response['headers'][$header] : null;
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
}
