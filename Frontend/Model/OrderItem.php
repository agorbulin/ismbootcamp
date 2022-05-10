<?php

namespace Frontend\Model;

class OrderItem extends Base
{
    public function getOrderProductItems($cartId)
    {
        $sth = $this->pdo->prepare("SELECT p.`Id`, p.`Name`, p.`Price`, c.`qty`
                                    FROM `products` p
                                    JOIN `cart_item` c ON c.product_id=p.Id
                                    WHERE `cart_id`=?");
        $sth->execute([$cartId]);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProuctData($cartId)
    {
        $sth = $this->pdo->prepare("SELECT c.`product_id`, c.`qty`, p.`Price` 
                                    FROM `cart_item` c
                                    JOIN `products` p ON c.product_id=p.Id
                                    WHERE `cart_id`=?");
        $sth->execute([$cartId]);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addOrderItem($data, $orderId)
    {
        $sth = $this->pdo->prepare("INSERT INTO `order_item`(`order_id`, `product_id`, `price`, `qty`) VALUES (:order_id, :product_id, :price, :qty)");
        return $sth->execute([
            ':order_id' => $orderId,
            ':product_id' => $data['product_id'],
            ':price' => $data['Price'],
            ':qty' => $data['qty'],
        ]);
    }
}
