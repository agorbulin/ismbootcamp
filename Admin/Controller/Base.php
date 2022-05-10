<?php

namespace Admin\Controller;

class Base
{
    protected $session;
    public $request;

    public function __construct()
    {
        $this->session = new \Session();
        $this->request = new \Request();
        $this->isLoggedIn();
    }

    private function isLoggedIn()
    {
        if (!$this->session->issetSessionKey() && $_SERVER['REQUEST_URI'] != "/admin/user/login") {
            header("Location: /admin/user/login");
            die;
        }
    }
}