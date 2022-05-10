<?php

class Application
{
    public function run()
    {
        try {
            $router = new Router();
            $router->run();
        } catch (\Exception $e) {
            Log::logErrors($e->getMessage(), get_class($e), $e->getFile(), $e->getLine());
            $objController = new Frontend\Controller\Error503;
            $objController->errorAction();
        } catch (\Error $e) {
            Log::logErrors($e->getMessage(), get_class($e), $e->getFile(), $e->getLine());
            $objController = new Frontend\Controller\Error503;
            $objController->errorAction();
        }
    }

    public static function render($file, $data = [], $prefix = 'Frontend')
    {
        extract($data);
        $filePath = '../' . $prefix . '/View/' . $file;
        if (!is_readable($filePath)) {
            $objController = new Frontend\Controller\Error503;
            $objController->errorAction();
            throw new Exception("Path $filePath not found");
        }
        require_once "../$prefix/View/header.phtml";
        require_once $filePath;
    }
}
