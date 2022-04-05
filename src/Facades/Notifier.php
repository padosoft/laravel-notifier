<?php

namespace Padosoft\Laravel\Notification\Notifier\Facades;

use Illuminate\Support\Facades\Facade;

class Notifier extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'notifier';
    }

}
