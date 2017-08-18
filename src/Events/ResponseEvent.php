<?php

namespace Appstract\LushHttp\Events;

use Appstract\LushHttp\Response\LushResponse;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ResponseEvent
{
    use Dispatchable, SerializesModels;

    public $response;

    /**
     * Create a new event instance.
     *
     * @param LushResponse $response
     * @return void
     */
    public function __construct(LushResponse $response)
    {
        $this->response = $response;
    }
}