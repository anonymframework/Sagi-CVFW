<?php

/**
 * Created by PhpStorm.
 * User: sagi
 * Date: 27.07.2016
 * Time: 21:02
 */
class Controller
{

    /**
     * @var string
     */
    protected $loginUri = '/auth/login';

    /**
     * @var string
     */
    protected $redirectUri = '/home';

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

    /**
     * @return bool
     */
    public function isLogined()
    {
        return isset($_SESSION['user_session']);
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        if ($this->isLogined()) {
            App::redirect($this->redirectUri);
        }

        $login = $this->db()->setTable('users')->select("username, email, registered_date")->where('username', $username)->where('password', md5($password));

        $fetch = $login->fetch();

        if ($fetch) {
            $_SESSION['user_session'] = base64_encode(serialize($fetch));

            App::redirect($this->redirectUri);
        } else {
            return false;
        }
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function register($parameters)
    {
        if ($this->isLogined()) {
            App::redirect($this->redirectUri);
        }

        $register = $this->db()->setTable('users')->create($parameters);

        return $register;
    }

    /**
     * @return Database
     */
    public function db()
    {
        return static::$db;
    }

}