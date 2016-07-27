<?php

namespace Controllers;


class Home extends \Controller
{

    public function index()
    {
        $this->login('admin', 'sanane123');
    }


}