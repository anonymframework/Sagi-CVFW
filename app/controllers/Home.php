<?php

namespace Controllers;


class Home
{

    public function index()
    {
        echo 'hello world';
    }

    public function test($message)
    {
        echo $message;
    }

}