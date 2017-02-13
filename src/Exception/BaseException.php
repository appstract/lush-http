<?php

namespace Appstract\LushHttp\Exception;

use RuntimeException;
use Exception;

abstract class BaseException extends RuntimeException
{

    /**
     * BaseException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}