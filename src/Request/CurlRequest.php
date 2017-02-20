<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Exception\LushException;
use Appstract\LushHttp\Exception\LushRequestException;
use Appstract\LushHttp\Request\Adapter\AdapterInterface;

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
    public $headers;

    /**
     * @var array
     */
    protected $defaultHeaders = [
        'X-Http-Client: Lush Http',
        'X-Lush-Http: 1',
    ];

    /**
     * @var array
     */
    protected $options = [];
    /**
     * @var array
     */
    protected $curlOptions = [];

    /**
     * @var
     */
    protected $adapter = Adapter\Curl::class;

    /**
     * @var
     */
    protected $client;

    /**
     * CurlRequest constructor.
     */
    public function __construct()
    {
        // Check for alternative adapters
        if (defined('LUSH_CURL_ADAPTER')) {
            if (! class_exists(LUSH_CURL_ADAPTER)) {
                throw new LushException(sprintf('Driver %s not found', LUSH_CURL_ADAPTER));
            }

            if (! class_implements(AdapterInterface::class)) {
                throw new LushException(sprintf('Driver %s must implement %s', LUSH_CURL_ADAPTER, AdapterInterface::class));
            }

            $this->adapter = LUSH_CURL_ADAPTER;
        }

        $this->client = new $this->adapter();
    }

    /**
     *  Merge default Curl options with given options.
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
     * Sends the Curl requests and returns result array.
     *
     * @return array
     */
    protected function makeRequest()
    {
        // init Curl
        $this->client->init($this->payload['url']);
        $this->client->setOptions($this->curlOptions);

        // get results
        $content = $this->client->execute();
        $headers = $this->client->getInfo();

        if ($content === false) {
            $error = [
                'code'      => $this->client->getErrorCode,
                'message'   => $this->client->getErrorMessage,
            ];

            $this->client->close();

            throw new LushRequestException($this, $error);
        }

        $this->client->close();

        return compact('content', 'headers');
    }
}
