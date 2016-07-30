<?php

namespace Controllers;


class Auth extends \Controller
{
    public function login()
    {

        if ($_POST) {
            $login = $this->Authlogin($_POST['username'], $_POST['password']);
        }

        $this->view('login');
    }

    public function logout()
    {
        $this->AuthExit();
    }
}