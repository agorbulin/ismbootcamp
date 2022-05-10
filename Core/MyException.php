<?php

class MyException
{
    public function __construct()
    {
        set_error_handler([$this, 'errorHandler']);
        ob_start();
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    public function errorHandler($level, $message, $file, $line)
    {
        Log::logErrors($message, $level, $file, $line);
        return true;
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            Log::logErrors($error['message'], $error['type'], $error['file'], $error['line']);
            ob_end_clean();
        } else {
            ob_end_flush();
        }
    }

    public function exceptionHandler($e)
    {
        Log::logErrors($e->getMessage(), get_class($e), $e->getFile(), $e->getLine());
    }
}