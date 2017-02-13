<?php

namespace Appstract\LushHttp\Exception;

class LushRequestException extends BaseException
{

    public $request;

    /**
     * RequestException constructor.
     *
     * @param string $request
     * @param array  $error
     */
    public function __construct($request, array $error)
    {
        $this->request = $request;
        parent::__construct($error['message'], $error['code']);
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

}