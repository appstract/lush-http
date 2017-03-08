<?php

namespace Appstract\LushHttp\Request\Adapter;

class Curl implements AdapterInterface
{
    /**
     * The curl object.
     *
     * @var null
     */
    protected $ch = null;

    /**
     * Init curl object with url.
     *
     * @param $url
     */
    public function init($url)
    {
        $this->ch = curl_init($url);
    }

    /**
     * Set options array.
     *
     * @param array $curlOptions
     * @param array $lushOptions
     */
    public function setOptions(array $curlOptions, array $lushOptions = null)
    {
        curl_setopt_array($this->ch, $curlOptions);
    }

    /**
     * Execute the request.
     *
     * @return mixed
     */
    public function execute()
    {
        return curl_exec($this->ch);
    }

    /**
     * Get request info (headers).
     *
     * @return mixed
     */
    public function getInfo()
    {
        return curl_getinfo($this->ch);
    }

    /**
     * Get curl error code.
     *
     * @return int
     */
    public function getErrorCode()
    {
        return curl_errno($this->ch);
    }

    /**
     * Get curl error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return curl_error($this->ch);
    }

    /**
     * Close the connection.
     */
    public function close()
    {
        curl_close($this->ch);
    }
}
