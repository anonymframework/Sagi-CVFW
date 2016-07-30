<?php

/**
 * Created by PhpStorm.
 * User: sagi
 * Date: 30.07.2016
 * Time: 13:38
 */
class Session
{

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return $_SESSION[$name];
    }

    /**
     * @param $name
     * @param null $value
     */
    public static function set($name, $value = null)
    {
        if (is_null($value)) {
            if (is_array($name)) {
                foreach ($name as $item => $value) {
                    $_SESSION[$item] = $value;
                }
            }
        } else {
            $_SESSION[$name] = $value;
        }

        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function delete($name)
    {
        unset($_SESSION[$name]);

        return true;
    }

    /**
     * @return bool
     */
    public static function clean()
    {
        $_SESSION = [];
        return true;
    }
}