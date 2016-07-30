<?php

namespace Controllers;


class Auth extends \Controller
{
    public function login()
    {

        if ($_POST) {
            $login = $this->Authlogin($_POST['username'], $_POST['password']);

            var_dump($login);
        }

        $this->view('login');
    }
}