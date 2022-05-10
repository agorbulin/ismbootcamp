<?php

namespace Admin\Model;

class Order extends Base
{
    public function getOrders()
    {
        $res = $this->pdo->query("SELECT `id`, `email`, `total`, `created_at` FROM `order`");
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOrderInfo($id)
    {
        $sth = $this->pdo->prepare("SELECT `id`, `email`, `total`, `created_at`, `address`, `visitor_id` FROM `order` WHERE `id`=?");
        $sth->execute([$id]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function getOrderItems($id)
    {
        $sth = $this->pdo->prepare("SELECT `id`, `price`, `qty`, `product_id` FROM `order_item` WHERE `order_id`=?");
        $sth->execute([$id]);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
}
