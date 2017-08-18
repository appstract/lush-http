<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Response\LushResponse;
use Appstract\LushHttp\Exception\LushException;
use Appstract\LushHttp\Exception\LushRequestException;
use Appstract\LushHttp\Request\Adapter\AdapterInterface;

abstract class CurlRequest
{
    public $method;

    public $payload;

    public $options = [];

    protected $curlOptions = [];

    protected $defaultHeaders = [
        'X-Http-Client: Lush Http',
        'X-Lush-Http: 1',
    ];

    protected $adapter = Adapter\Curl::class;

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
        $this->curlOptions = array_replace(RequestOptions::$defaultCurlOptions, $this->curlOptions);
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
