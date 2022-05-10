<?php

namespace Frontend\Model;

class Visitor extends Base
{
    public function addVisitor($session_id)
    {
        $sth = $this->pdo->prepare("INSERT INTO `visitor` (`session_id`) VALUES(:session_id)");
        $sth->execute([':session_id' => $session_id]);
    }

    public function getVisitorId($session_id)
    {
        $sth = $this->pdo->prepare("SELECT `id` from `visitor` WHERE `session_id`=?");
        $sth->execute([$session_id]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }
}
