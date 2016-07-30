<?php

/**
 * Created by PhpStorm.
 * User: sagi
 * Date: 30.07.2016
 * Time: 13:43
 */
class Cookie
{

    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    /**
     * @param $name
     * @param $value
     * @param int $time
     * @return bool
     */
    public static function set($name, $value, $time = 3600)
    {
        return setcookie($name, $value, time() + $time);
    }

    /**
     * @param $name
     * @return bool
     */
    public static function delete($name)
    {
        return setcookie($name, '', time() - 3600);
    }

    /**
     * @return bool
     */
    public static function clean()
    {
        foreach ($_COOKIE as $index => $value) {
            setcookie($index, '', time() - 3600);
        }

        return true;
    }
}