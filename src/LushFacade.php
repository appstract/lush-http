<?php

namespace Appstract\LushHttp;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Appstract\LushHttp\Lush
 */
class LushFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Lush::class;
    }
}
