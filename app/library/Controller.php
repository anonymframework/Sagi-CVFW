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

    /**
     * @var string
     */
    protected $loginSessionName = 'user_session';

    /**
     * @var Database
     */
    public static $db;

    /**
     * @var View
     */
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
        if (Session::has('errors')) {
            $this->with('errors', Session::get('errors'));
        }

        $this->with('isLogined', $this->isLogined());

        Session::delete('errors');

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
        return Session::has($this->loginSessionName);
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function Authlogin($username, $password)
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
            Session::set($this->loginSessionName, base64_encode(serialize($fetch)));

            App::redirect($this->redirectUri);
        } else {
            Session::set('errors', array(
                'Giriş İşlemi sırasında bir hata oluştu'
            ));
        }
    }

    /**
     * @return bool
     */
    public function AuthExit()
    {
        Session::delete($this->loginSessionName);

        App::redirect($this->redirectUri);
        return true;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function Authregister($parameters)
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

        $datas = $validator->datas();

        $datas['password'] = md5($datas['password']);

        $register = $this->db()->setTable('users')->create($datas);

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