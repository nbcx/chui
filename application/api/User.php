<?php
namespace controller\api;

use nb\Controller;

class User extends Controller {

    public function login() {
        $this->layout(false);
        $this->display('login');
    }


    public function register() {
        $result = $this->middle(true,'register');
        quit($result);
    }

}