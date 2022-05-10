<?php

namespace Frontend\Controller;

use Application;

class Error503
{
    public function errorAction()
    {
        header("HTTP/1.1 503 Service Unavailable");
        Application::render('error/503.phtml', ['title' => 'error page']);
    }
}