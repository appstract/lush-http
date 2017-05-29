<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Exception\LushException;
use Appstract\LushHttp\Exception\LushRequestException;
use Appstract\LushHttp\Request\Adapter\AdapterInterface;
use Appstract\LushHttp\Response\LushResponse;

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
    public $options = [];

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
        if (defined('LUSH_ADAPTER')) {
            if (! class_exists(LUSH_ADAPTER)) {
                throw new LushException(sprintf('Adapter %s not found', LUSH_ADAPTER));
            }

            if (! in_array(AdapterInterface::class, class_implements(LUSH_ADAPTER))) {
                throw new LushException(sprintf('Adapter %s must implement %s', LUSH_ADAPTER, AdapterInterface::class));
            }

            $this->adapter = LUSH_ADAPTER;
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
            CURLOPT_FAILONERROR     => true,
            CURLOPT_USERAGENT       => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Lush Http Client',
            //CURLOPT_COOKIEJAR       => storage_path('app/lushcookie.txt'),
            //CURLOPT_COOKIEFILE      => storage_path('app/lushcookie.txt'),
        ];

        $this->curlOptions = array_replace($defaultOptions, $this->curlOptions);
    }

    /**
     * Sends the Curl requests and returns result array.
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    protected function makeRequest()
    {
        // init Curl
        $this->client->init($this->payload['url']);
        $this->client->setOptions($this->curlOptions, $this->options);

        // get results
        $content = $this->client->execute();
        $headers = $this->client->getInfo();

        $response = new LushResponse(compact('content', 'headers'), $this);

        // handle errors
        if ($content === false || substr($headers['http_code'], 0, 1) != 2) {
            $error = [
                'code'      => $this->client->getErrorCode(),
                'message'   => $this->client->getErrorMessage(),
                'response'   => $response,
            ];

            $this->client->close();

            throw new LushRequestException($this, $error);
        }

        $this->client->close();

        return $response;
    }
}
