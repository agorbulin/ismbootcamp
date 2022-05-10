<?php

namespace Frontend\Model;

class Base
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = \Db::getInstance()->getConnection();
    }
}