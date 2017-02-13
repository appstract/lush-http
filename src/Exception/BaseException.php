<?php

namespace Appstract\LushHttp\Exception;

use Exception;
use RuntimeException;

abstract class BaseException extends RuntimeException
{
    /**
     * BaseException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
