<?php

namespace Frontend\Controller;

use Application;

class Error404
{
    public function errorAction()
    {
        header("HTTP/1.1 404 Not Found");
        Application::render('error/404.phtml', ['title' => 'error page']);
        die;
    }
}