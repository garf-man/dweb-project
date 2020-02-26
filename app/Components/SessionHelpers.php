<?php


namespace App\Components;


class SessionHelpers
{
    static function addByKey($key, $data)
    {
        $_SESSION[$key] = $data;
    }

    static function destroyByKey($key)
    {

        unset($_SESSION[$key]);
    }

    /**
     * @return array|null
     */
    static function user()
    {
        return isset($_SESSION['user']) ? (object)$_SESSION['user'] : null;
    }

    static function getCurrencies(){
        if(empty($_SESSION['currency'])){
            $_SESSION['currency'] = ['name'=>'BYN','value'=>1];
        }
            return $_SESSION['currency']['name'];
    }

}

