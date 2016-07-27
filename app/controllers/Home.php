<?php

namespace Controllers;


class Home extends \Controller
{

    public function index()
    {
        $this->with('message', 'hello world')->view('index');
    }


}