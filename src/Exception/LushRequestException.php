<?php

namespace Appstract\LushHttp\Exception;

class LushRequestException extends BaseException
{
    /**
     * @var string
     */
    public $request;

    /**
     * @var mixed
     */
    public $content;

    /**
     * RequestException constructor.
     *
     * @param string $request
     * @param array  $error
     */
    public function __construct($request, array $error)
    {
        $this->request = $request;
        $this->content = $error['content'];

        parent::__construct($error['message'], $error['code']);
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
