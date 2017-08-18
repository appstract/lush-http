<?php

namespace Appstract\LushHttp\Events;

use Illuminate\Queue\SerializesModels;
use Appstract\LushHttp\Response\LushResponse;
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
