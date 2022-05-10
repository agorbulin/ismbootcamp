<?php

class Router
{
    private $controller;
    private $action;
    private $params;

    private function getRoute()
    {
        $uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        if ($uri[0] == 'admin') {
            $prefix = ucfirst(array_shift($uri));
        } else {
            $prefix = 'Frontend';
        }
        $controllerName = $uri[0] ?? '';
        $actionName = $uri[1] ?? '';
        $paramName = $uri[2] ?? '';
        $paramValue = $uri[3] ?? '';
        if ($controllerName == 'search' || ($controllerName == 'cart' && !$actionName)) {
            $actionName = 'index';
        }
        if ($controllerName == 'index.php' || !$controllerName) {
            $controllerName = 'product';
            $actionName = 'list';
        }
        $this->controller = $prefix . '\\Controller\\' . ucfirst($controllerName);
        $this->action = $actionName . 'Action';
        if ('id' == $paramName) {
            $this->params['value'] = $paramValue;
        } else {
            $this->params['value'] = '';
        }
    }

    public function run()
    {
        $this->getRoute();

        if (class_exists($this->controller)) {
            $objController = new $this->controller;
            if (method_exists($objController, $this->action)) {
                $objController->{$this->action}($this->params['value']);
            } else {
                $objController = new Frontend\Controller\Error404;
                $objController->errorAction();
                throw new Exception("action $this->action does not exist");
            }
        } else {
            $objController = new Frontend\Controller\Error404;
            $objController->errorAction();
            throw new Exception("controller $this->controller does not exist");
        }
    }
}