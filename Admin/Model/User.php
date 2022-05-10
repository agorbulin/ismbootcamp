<?php

namespace Admin\Model;

class User
{
    public function getUser()
    {
        $res = \Db::getInstance()->getConnection()->query("SELECT `id`, `login`, `password` FROM `admin_users`");
        return $res->fetch(\PDO::FETCH_ASSOC);
    }
}