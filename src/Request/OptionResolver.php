<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Exception\LushException;

class OptionResolver
{
    /**
     * @var array
     */
    public static $resolve = [
        'username'          => CURLOPT_USERNAME,
        'user'              => CURLOPT_USERNAME,
        'password'          => CURLOPT_USERPWD,
        'user_agent'        => CURLOPT_USERAGENT,
        'ua'                => CURLOPT_USERAGENT,
        'timeout'           => CURLOPT_TIMEOUT,
        'connect_timeout'   => CURLOPT_CONNECTTIMEOUT,
        'encoding'          => CURLOPT_ENCODING,
        'follow_redirects'  => CURLOPT_FOLLOWLOCATION,
    ];

    /**
     * @param $option
     *
     * @return mixed
     */
    public static function resolve($option)
    {
        if (isset(self::$resolve[$option])) {
            return self::$resolve[$option];
        }

        throw new LushException(sprintf("Option '%s' is invalid", $option));
    }
}
