<?php

namespace Appstract\LushHttp\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Events\Dispatcher;
use Appstract\LushHttp\Exception\LushRequestException;

class RequestExceptionEvent extends Dispatcher
{
    use SerializesModels;

    public $exception;

    /**
     * Create a new event instance.
     *
     * @param LushRequestException $exception
     */
    public function __construct(LushRequestException $exception)
    {
        $this->exception = $exception;
    }
}
