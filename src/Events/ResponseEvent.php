<?php

namespace Appstract\LushHttp\Events;

use Appstract\LushHttp\Response\LushResponse;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\SerializesModels;

class ResponseEvent extends Dispatcher
{
    use SerializesModels;

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
