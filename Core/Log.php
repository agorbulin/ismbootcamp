<?php

class Log
{
    public static function logs($message, $fileName, $level = '', $file = '', $line = '')
    {
        switch ($level) {
            case 1:
                $level = 'E_ERROR';
                break;
            case 2:
                $level = 'E_WARNING';
                break;
            case 4:
                $level = 'E_PARSE';
                break;
            case 8:
                $level = 'E_NOTICE';
                break;
            case 16:
                $level = 'E_CORE_ERROR';
                break;
            case 32:
                $level = 'E_CORE_WARNING';
                break;
            case 64:
                $level = 'E_COMPILE_ERROR';
                break;
            case 128:
                $level = 'E_COMPILE_WARNING';
                break;
            case 256:
                $level = 'E_USER_ERROR';
                break;
            case 512:
                $level = 'E_USER_WARNING';
                break;
            case 1024:
                $level = 'E_USER_NOTICE';
                break;
            case 2048:
                $level = 'E_STRICT';
                break;
            case 4096:
                $level = 'E_RECOVERABLE_ERROR';
                break;
            case 8192:
                $level = 'E_DEPRECATED';
                break;
            case 16384:
                $level = 'E_USER_DEPRECATED';
                break;
            default:
                $level;
        }
        self::isLogPath();
        error_log("[" . date('Y-m-d H:i:s') . "] report.$level: $message in $file on line $line\n", 3,
            LOG_DIR . $fileName);
    }

    public static function logErrors($message, $level, $file, $line)
    {
        self::logs($message, 'system.log', $level, $file, $line,);
    }

    public static function logImport($message = '')
    {
        self::logs($message, 'product_import.log');
    }

    public static function isLogPath()
    {
        if (!file_exists(LOG_DIR)) {
            mkdir(LOG_DIR, 0777, true);
        }
    }
}
