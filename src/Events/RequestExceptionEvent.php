<?php

namespace Appstract\LushHttp\Events;

use Appstract\LushHttp\Exception\LushRequestException;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\SerializesModels;

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
