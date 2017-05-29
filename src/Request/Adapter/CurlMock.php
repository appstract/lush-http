<?php

namespace Appstract\LushHttp\Request\Adapter;

use Exception;
use Appstract\LushHttp\Exception\LushRequestException;

class CurlMock implements AdapterInterface
{
    /**
     * The curl object.
     *
     * @var null
     */
    protected $ch = null;

    /**
     * @var array
     */
    protected $curlOptions = [];

    /**
     * @var array
     */
    protected $lushOptions = [];

    /**
     * @var bool
     */
    protected $executed = false;

    /**
     * @var array
     */
    protected $headers = [
        'content_type'              => 'text/html; charset=UTF-8',
        'header_size'               => 611,
        'request_size'              => 235,
        'ssl_verify_result'         => 0,
        'redirect_count'            => 0,
        'size_upload'               => 0.0,
        'size_download'             => 219.0,
        'speed_download'            => 11810.0,
        'speed_upload'              => 0.0,
        'download_content_length'   => -1.0,
        'upload_content_length'     => -1.0,
        'starttransfer_time'        => 0.018515,
        'redirect_time'             => 0.0,
        'redirect_url'              => '',
        'primary_ip'                => '127.0.0.1',
        'certinfo'                  => [],
        'primary_port'              => 80,
        'local_ip'                  => '127.0.0.1',
        'local_port'                => 51148,
    ];

    /**
     * Init curl object with url.
     *
     * @param $url
     */
    public function init($url)
    {
        $this->ch = $url;
    }

    /**
     * Set options array.
     *
     * @param array $curlOptions
     * @param array $lushOptions
     */
    public function setOptions(array $curlOptions, array $lushOptions = null)
    {
        $this->curlOptions = $curlOptions;
        $this->lushOptions = $lushOptions;
    }

    /**
     * Execute the request.
     *
     * @return mixed
     * @throws Exception
     */
    public function execute()
    {
        if (! $this->ch) {
            throw new Exception('Curl request not initiated');
        }

        $statusCode = (int) isset($this->lushOptions['return_status']) ? $this->lushOptions['return_status'] : 200;
        $contentType = isset($this->lushOptions['return_content_type']) ? $this->lushOptions['return_content_type'] : 'text';

        $this->headers['url'] = $this->ch;
        $this->headers['http_code'] = $statusCode;

        $this->executed = true;

        return $this->createResponse($statusCode, $contentType);
    }

    /**
     * Get request info (headers).
     *
     * @return mixed
     * @throws Exception
     */
    public function getInfo()
    {
        if (! $this->executed) {
            throw new Exception('Curl request not executed');
        }

        return $this->headers;
    }

    /**
     * Get curl error code.
     *
     * @return int
     * @throws Exception
     */
    public function getErrorCode()
    {
        if (! $this->executed) {
            throw new Exception('Curl request not executed');
        }

        return 0;
    }

    /**
     * Get curl error message.
     *
     * @return string
     * @throws Exception
     */
    public function getErrorMessage()
    {
        if (! $this->executed) {
            throw new Exception('Curl request not executed');
        }

        return '';
    }

    /**
     * Close the connection.
     */
    public function close()
    {
        $this->ch = null;
    }

    /**
     * Create a example response based on statuscode.
     *
     * @param $statusCode
     * @param $contentType
     *
     * @return string
     */
    protected function createResponse($statusCode, $contentType)
    {
        switch ($statusCode) {
            case 200:
            case 201:
                return $this->createContent($contentType);
            default:
                // fail on error
                if ($this->curlOptions[45]) {
                    throw new LushRequestException($this, ['message' => sprintf('%d - Mocked server error', $statusCode), 'code' => $statusCode, 'response' => 'false']);
                }

                return json_encode(['url' => $this->ch, 'status' => sprintf('Error: %d', $statusCode)]);
        }
    }

    /**
     * Create sample content for response.
     *
     * @param $type
     *
     * @return string
     */
    protected function createContent($type)
    {
        if ($type == 'json') {
            $this->headers['content_type'] = 'application/json; charset=UTF-8';

            return json_encode(['url' => $this->ch, 'status' => 'ok']);
        } elseif ($type == 'xml') {
            $this->headers['content_type'] = 'text/xml; charset=UTF-8';

            return '<?xml version="1.0" encoding="UTF-8"?><result><url>'.$this->ch.'</url><status>ok</status></result>';
        }

        return 'ok';
    }
}
