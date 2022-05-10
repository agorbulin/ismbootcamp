<?php

class Db
{
    private static $instance = null;
    private $conn;
    private $config = CONFIG;

    private function __construct()
    {
        $this->conn = new PDO(
            sprintf('mysql:host=%s;dbname=%s', $this->config['dbhost'], $this->config['dbname']),
            $this->config['login'],
            $this->config['password'],
        );
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new db();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}