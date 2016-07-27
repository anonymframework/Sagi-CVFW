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

    public function __construct(array $configs = [])
    {
        $this->configs = $configs;
        $this->url = $_SERVER['PATH_INFO'];
    }

    public function handleRequest()
    {

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