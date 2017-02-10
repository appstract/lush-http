<?php

namespace Appstract\Lush;


abstract class Request
{
    public $method;
    public $payload;

    protected $options = [];
    protected $curlOptions = [];

    protected function mergeCurlOptions()
    {
        $defaultOptions = [
            CURLOPT_RETURNTRANSFER  => true,            // return web page
            CURLOPT_HEADER          => true,            // return headers
            CURLOPT_FOLLOWLOCATION  => true,            // follow redirects
            CURLOPT_ENCODING        => '',              // handle compressed
            CURLOPT_CONNECTTIMEOUT  => 60,             // time-out on connect
            CURLOPT_TIMEOUT         => 300,             // time-out on response
            //CURLOPT_POST            => true,            // true for post
            //CURLOPT_POSTFIELDS      => $paramsString,   // post parameters,
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_USERAGENT       => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Lush Http Client',
            //CURLOPT_COOKIEJAR       => storage_path('app/lushcookie.txt'),
            //CURLOPT_COOKIEFILE      => storage_path('app/lushcookie.txt'),
        ];

        //$this->curlOptions = $defaultOptions;
        $this->curlOptions = array_replace($defaultOptions, $this->options);
        //dd($this->curlOptions);
    }

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