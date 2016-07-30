<?php

namespace Controllers;


class Home extends \Controller
{

    public function index()
    {
        return $this->view('index');
    }


}