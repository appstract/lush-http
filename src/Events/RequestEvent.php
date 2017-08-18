<?php

namespace Appstract\LushHttp\Events;

use Illuminate\Queue\SerializesModels;
use Appstract\LushHttp\Request\LushRequest;
use Illuminate\Foundation\Events\Dispatchable;

class RequestEvent
{
    use Dispatchable, SerializesModels;

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
