<?php


class App
{
    /**
     * @var array
     */
    protected $configs;

    /**
     * @var string
     */
    private $url;


    /**
     * @var
     */
    protected $controller;

    public function __construct(array $configs = [])
    {
        $this->configs = $configs;
        $this->url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
    }

    public function handleRequest()
    {
        $this->url = substr($this->url, 1, strlen($this->url));
        $parsed = explode("/", $this->url);

        switch (count($parsed)) {

            case 1:
                if ($parsed[0] === "") {
                    $this->getDefaultController();
                }
                break;
            case 2:
                $controller = $parsed[0];
                $method = $parsed[1];

                $this->callMethodByController($controller, $method);
                break;
            case 3:
                $controller = $parsed[0];
                $method = $parsed[1];
                $arg = $parsed[2];

                $this->callMethodByController($controller, $method, $arg);
                break;

            default:
                $controller = $parsed[0];
                $method = $parsed[1];

                unset($parsed[0]);
                unset($parsed[1]);

                $this->callMethodByController($controller, $method, $parsed);
                break;

        }
    }

    /**
     *
     */
    public function getDefaultController()
    {
        $defautlController = $this->configs['default_controller'];

        $this->createControllerInstance($defautlController);
        $this->callMethodByController($this->controller, 'index', []);
    }

    /**
     * @param $controller
     * @param $method
     * @param array $args
     */
    public function callMethodByController($controller, $method, $args = [])
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        $this->createControllerInstance($controller);

        call_user_func_array(array($this->controller, $method), $args);
    }

    /**
     * @param $controller
     * @return mixed
     */
    public function createControllerInstance($controller)
    {
        $controller = $this->getFullControllerNamespace($controller);

        $this->controller = new $controller;
    }

    /**
     * @param $controller
     * @return string
     */
    public function getFullControllerNamespace($controller)
    {
        return "Controllers\\" . $controller;
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @param array $configs
     * @return App
     */
    public function setConfigs($configs)
    {
        $this->configs = $configs;
        return $this;
    }


}