<?php

namespace Frontend\Model;

class Order extends Base
{
    public function isVisitorExist($visitorId)
    {
        $sth = $this->pdo->prepare("SELECT `id` FROM `order` WHERE `visitor_id` = ? ");
        $sth->execute([$visitorId]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function saveToOrder($params)
    {
        $sth = $this->pdo->prepare("INSERT INTO `order`(`visitor_id`, `created_at`, `address`, `email`, `total`) VALUES (:visitor_id, NOW(), :address, :email, :total)");
        $sth->execute([
            ':visitor_id' => $params['visitor_id'],
            ':address' => $params['address'],
            ':email' => $params['email'],
            ':total' => $params['total'],
        ]);
    }

    public function getOrderId($params)
    {
        $visitorId = $params['visitor_id'];
        $isVisitorExist = $this->isVisitorExist($visitorId);
        if (isset($visitorId) && !$isVisitorExist) {
            $this->saveToOrder($params);
            $isVisitorExist = $this->isVisitorExist($visitorId);
        }
        return $isVisitorExist;
    }
}