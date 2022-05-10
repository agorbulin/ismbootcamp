<?php

namespace Frontend\Model;

class CartItem extends Base
{
    public function getCartProductItems($cartId)
    {
        $sth = $this->pdo->prepare("SELECT p.`Id`, p.`Name`, p.`Price`, c.`id`, c.`qty`
                                    FROM `products` p
                                    JOIN `cart_item` c ON p.Id=c.product_id
                                    WHERE `cart_id`=?");
        $sth->execute([$cartId]);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveToCartItem($data)
    {
        if (isset($data['cart_id']) && $cartItem = $this->getCartItemData($data)) {
            $this->updateCartItem($data, $cartItem['id']);
        } else {
            $this->addCartItem($data);
        }
    }

    public function getCartItemData($data)
    {
        $sth = $this->pdo->prepare("SELECT `id`, `qty` FROM `cart_item` WHERE `cart_id` = :cart_id AND `product_id`= :product_id");
        $sth->execute([
            ':cart_id' => $data['cart_id'],
            ':product_id' => $data['product_id']
        ]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateCartItem($data, $id)
    {
        $sth = $this->pdo->prepare("UPDATE `cart_item` SET `qty` = :qty WHERE `id` = :id");
        $sth->execute([
            ':qty' => $data['qty'],
            ':id' => $id
        ]);
    }

    public function addCartItem($data)
    {
        $sth = $this->pdo->prepare("INSERT INTO `cart_item`(`cart_id`, `qty`, `product_id`) VALUES (:cart_id, :qty, :product_id)");
        $sth->execute([
            ':cart_id' => $data['cart_id'],
            ':qty' => $data['qty'],
            ':product_id' => $data['product_id']
        ]);
    }

    public function deleteItem($id, $card_id)
    {
        $sth = $this->pdo->prepare("DELETE FROM `cart_item` WHERE `cart_id` = :cart_id AND `id` = :id");
        $sth->execute([':id' => $id, ':cart_id' => $card_id]);
    }
}
