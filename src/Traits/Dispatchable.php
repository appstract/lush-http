<?php

/**
 * Author: Taylor Otwell
 * Source: https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Events/Dispatchable.php
 */

namespace Appstract\LushHttp\Traits;

trait Dispatchable
{
    /**
     * Dispatch the event with the given arguments.
     *
     * @return void
     */
    public static function dispatch()
    {
        return event(new static(...func_get_args()));
    }
    /**
     * Broadcast the event with the given arguments.
     *
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    public static function broadcast()
    {
        return broadcast(new static(...func_get_args()));
    }
}
