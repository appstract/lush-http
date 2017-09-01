<?php

namespace Appstract\LushHttp\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Appstract\LushHttp\Exception\LushRequestException;

class RequestExceptionEvent
{
    use Dispatchable, SerializesModels;

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
