<?php

namespace Appstract\LushHttp\Events;

use Appstract\LushHttp\Request\LushRequest;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\SerializesModels;

class RequestEvent extends Dispatcher
{
    use SerializesModels;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param LushRequest $request
     * @return void
     */
    public function __construct(LushRequest $request)
    {
        $this->request = $request;
    }
}
