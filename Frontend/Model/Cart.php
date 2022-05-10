<?php

namespace Frontend\Model;

class Cart extends Base
{
    public function getCartIdByVisitorId($visitorId)
    {
        $sth = $this->pdo->prepare("SELECT `id` FROM `cart` WHERE `visitor_id` = ? ");
        $sth->execute([$visitorId]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function saveToCart($visitorId)
    {
        $sth = $this->pdo->prepare("INSERT INTO `cart`(`visitor_id`) VALUES (:visitor_id)");
        $sth->execute([':visitor_id' => $visitorId]);
    }

    public function getCartId($visitorId)
    {
        $cartId = $this->getCartIdByVisitorId($visitorId);
        if (isset($visitorId) && !$cartId) {
            $this->saveToCart($visitorId);
            $cartId = $this->getCartIdByVisitorId($visitorId);
        }
        return $cartId;
    }
}
