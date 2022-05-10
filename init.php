<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_DIR', __DIR__);
define('LOG_DIR', __DIR__ . '/var/log/');
define('CONFIG', require_once 'config.php');
define('COUNT_OF_PRODUCTS', 2);
require_once ROOT_DIR . '/Core/Session.php';
require_once ROOT_DIR . '/Core/Log.php';
require_once ROOT_DIR . '/Core/MyException.php';
require_once ROOT_DIR . '/Core/Application.php';
require_once ROOT_DIR . '/Core/Request.php';
require_once ROOT_DIR . '/Core/Db.php';
require_once ROOT_DIR . '/Core/Router.php';
new MyException();

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    $lastNsPos = strrpos($className, '\\');
    $namespace = substr($className, 0, $lastNsPos);
    $className = substr($className, $lastNsPos + 1);
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR . $className . '.php';
    if (!file_exists(ROOT_DIR . '/' . $fileName)) {
        $objController = new Frontend\Controller\Error503;
        $objController->errorAction();
        throw new \Exception("File $fileName does not exist");
    }
    require_once $fileName;
}

spl_autoload_register('autoload');
