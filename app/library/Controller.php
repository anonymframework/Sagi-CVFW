<?php

/**
 * Created by PhpStorm.
 * User: sagi
 * Date: 27.07.2016
 * Time: 21:02
 */
class Controller
{

    public static $db;

    protected static $view;

    /**
     * @param View $view
     */
    public static function setViewInstance(View $view)
    {
        static::$view = $view;
    }

    /**
     * @param $key
     * @param null $value
     * @return $this
     */
    public function with($key, $value = null)
    {
        static::$view->with($key, $value);

        return $this;
    }

    /**
     * @param $file
     * @return $this
     */
    public function view($file)
    {
        static::$view->render($file)->show();

        return $this;
    }

    public function login($usernameOrEmail, $password)
    {
        $login = $this->db()->setTable('users')->where('username', $usernameOrEmail)->orWhere('email', $usernameOrEmail)->where('password', $password);

        var_dump($login->get());
    }

    /**
     * @return Database
     */
    public function db()
    {
        return static::$db;
    }

}