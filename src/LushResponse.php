<?php

namespace Appstract\Lush;


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
     * Get the content of the result
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->response['content'];
    }

    /**
     * Get errors
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