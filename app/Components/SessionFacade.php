<?php


namespace App\Components;

/**
 * Class SessionFacade
 * @package App\Components
 * @method static user()
 */
class SessionFacade
{

    public static function  __callStatic($name, $arguments)
    {
       return (new SessionHelpers())->$name($arguments);
    }

}