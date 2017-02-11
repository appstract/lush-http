<?php

namespace Appstract\LushHttp\Response;


class ResponseContent
{

    /**
     * @var mixed
     */
    public $content;

    /**
     * @var mixed
     */
    public $isJson;

    /**
     * LushContent constructor.
     *
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;

        if ($this->isJson()) {
            $this->content = json_decode($this->content);
        }

        return $this->content;
    }

    /**
     * Check if content is json
     *
     * @return null
     */
    public function isJson()
    {
        if(!isset($this->isJson)){
            json_decode($this->content);
            $this->isJson = (json_last_error() == JSON_ERROR_NONE);
        }

        return $this->isJson;
    }

    /**
     * @param $name
     *
     * @return null
     */
    public function __get($name)
    {
        // check if the property is present in the content
        if(isset($this->content->{$name})){
            return $this->content->{$name};
        }

        return null;
    }
}