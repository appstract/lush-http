<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Exception\LushException;

class OptionResolver
{
    /**
     * Curl options.
     *
     * @var array
     */
    public static $curlOptions = [
        'username'          => CURLOPT_USERNAME,        // username for authentication
        'user_agent'        => CURLOPT_USERAGENT,       // custom user agent
        'ua'                => CURLOPT_USERAGENT,       // alias for custom user agent
        'timeout'           => CURLOPT_TIMEOUT,         // timeout
        'connect_timeout'   => CURLOPT_CONNECTTIMEOUT,  // timeout for connection
        'encoding'          => CURLOPT_ENCODING,        // custom encoding
        'follow_redirects'  => CURLOPT_FOLLOWLOCATION,  // follow redirects
        'fail_on_error'     => CURLOPT_FAILONERROR,     // throw exception if return code is not a success code
    ];

    /**
     * Lush options.
     *
     * @var array
     */
    public static $lushOptions = [
        'auto_format',          // automatic format response
        'password',             // password for authentication
        'return_status',        // (internal) used for testing return status
    ];

    /**
     * @param $option
     *
     * @return mixed
     */
    public static function resolve($option)
    {
        if (isset(self::$curlOptions[$option])) {
            return ['type' => 'curl_option', 'option' => self::$curlOptions[$option]];
        } elseif (in_array($option, self::$lushOptions)) {
            return ['type' => 'lush_option'];
        }

        throw new LushException(sprintf("Invalid option '%s'", $option));
    }
}
