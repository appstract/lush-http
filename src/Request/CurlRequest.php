<?php

namespace Appstract\LushHttp\Request;

abstract class CurlRequest
{
    /**
     * @var
     */
    public $method;
    /**
     * @var
     */
    public $payload;

    /**
     * @var array
     */
    protected $options = [];
    /**
     * @var array
     */
    protected $curlOptions = [];

    /**
     *  Merge default Curl options with given options
     */
    protected function mergeCurlOptions()
    {
        $defaultOptions = [
            CURLOPT_RETURNTRANSFER  => true,            // return web page
            CURLOPT_HEADER          => false,           // return headers
            CURLOPT_FOLLOWLOCATION  => true,            // follow redirects
            CURLOPT_ENCODING        => '',              // handle compressed
            CURLOPT_CONNECTTIMEOUT  => 60,              // time-out on connect
            CURLOPT_TIMEOUT         => 300,             // time-out on response
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_USERAGENT       => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Lush Http Client',
            //CURLOPT_COOKIEJAR       => storage_path('app/lushcookie.txt'),
            //CURLOPT_COOKIEFILE      => storage_path('app/lushcookie.txt'),
        ];

        $this->curlOptions = array_replace($defaultOptions, $this->options);
    }

    /**
     * Sends the Curl requests and returns result array
     *
     * @return array
     */
    protected function makeRequest()
    {
        // init Curl
        $request = curl_init($this->payload['url']);
        curl_setopt_array($request, $this->curlOptions);

        // get results
        $content        = curl_exec($request);
        $headers        = curl_getinfo($request);

        $errors = [
            'code'      => curl_errno($request),
            'message'   => curl_error($request)
        ];

        curl_close($request);

        return compact('content', 'headers', 'errors');
    }
}