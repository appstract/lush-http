<?php

namespace Appstract\LushHttp\Exception;

use Appstract\LushHttp\Events\RequestExceptionEvent;

class LushRequestException extends BaseException
{
    public $request;

    public $response;

    public $message;

    /**
     * RequestException constructor.
     *
     * @param string $request
     * @param array  $error
     */
    public function __construct($request, array $error)
    {
        $this->request = $request;
        $this->response = $error['response'];
        $this->message = $error['message'];

        if (! isset($error['message']) || empty($error['message'])) {
            $this->message = json_encode($this->getContent());
        }

        if (function_exists('event')) {
            event(new RequestExceptionEvent($this));
        }

        parent::__construct($this->message, $error['code']);
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
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->response->getResult();
    }
}
