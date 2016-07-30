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

    /**
     * @var string
     */
    protected $registerUri = '/auth/register';

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
     * @param array $datas
     * @param array $rules
     * @param array $filters
     * @param array $messages
     * @return Validator
     */
    public function validation($datas = [], $rules = [], $filters = [], $messages = [])
    {
        return new Validator($datas, $rules, $filters, $messages);
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

        $validator = $this->validation([
            'username' => $username,
            'password' => $password
        ], [
            'username' => 'required|alpha_numeric|digit_between:6,20',
            'password' => 'required|digit_min:6'
        ], [
            'username' => 'xss',
            'password' => 'xss'
        ]);


        if ($errors = $validator->errors()) {
            App::redirect($this->loginUri, null, $errors);
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

        $datas = array(
            'username' => $parameters['username'],
            'password' => $parameters['password'],
            'email' => $parameters['email']
        );

        $validator = $this->validation($datas, array(
            'username' => 'required|digit_max:25|alpha_numeric',
            'password' => 'required|digit_min:6',
            'email' => 'required|digit_min:6|email'
        ), array(
            'username' => 'xss',
            'password' => 'xss',
            'email' => 'strip_tags'
        ));

        if ($errors = $validator->errors()) {
            App::redirect($this->registerUri, null, $errors);
        }


        $register = $this->db()->setTable('users')->create($validator->datas());

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