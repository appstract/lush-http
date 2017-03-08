<?php

namespace Appstract\LushHttp\Request\Adapter;

interface AdapterInterface
{
    /**
     * @param $url
     *
     * @return void
     */
    public function init($url);

    /**
     * @param array $curlOptions
     * @param array $lushOptions
     *
     * @return void
     */
    public function setOptions(array $curlOptions, array $lushOptions);

    /**
     * @return mixed
     */
    public function execute();

    /**
     * @return mixed
     */
    public function getInfo();

    /**
     * @return mixed
     */
    public function getErrorCode();

    /**
     * @return mixed
     */
    public function getErrorMessage();

    /**
     * @return void
     */
    public function close();
}
