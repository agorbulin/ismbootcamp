<?php

namespace Admin\Model;

class Product extends Base
{
    public function getList()
    {
        $res = $this->pdo->query("SELECT `SKU`, `Id`, `Name`, `is_enabled` FROM `products`");
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getItem($id)
    {
        $sth = $this->pdo->prepare("SELECT `Id`, `SKU`, `Name`, `Qty`, `Description`, `Price`, `Image`, `is_enabled` FROM `products` WHERE `Id` = ? ");
        $sth->execute([$id]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function isProductExist($sku)
    {
        $sth = $this->pdo->prepare("SELECT `SKU` FROM `products` WHERE `SKU` = ? ");
        $sth->execute([$sku]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function saveProduct($params)
    {
        if (isset($params['SKU']) && $this->isProductExist($params['SKU'])) {
            $this->updateProduct($params);
        } else {
            $this->addProduct($params);
        }
    }

    public function updateProduct($params)
    {
        $sth = $this->pdo->prepare("UPDATE `products` SET `Qty` = :qty, `Name` = :name_product, `Description` = :description, `Price` = :price, `Image` = :image, `is_enabled`=:is_enabled WHERE `SKU` = :SKU");
        $sth->execute([
            ':SKU' => $params['SKU'],
            ':qty' => $params['qty'],
            ':name_product' => $params['name'],
            ':description' => $params['description'],
            ':price' => $params['price'],
            ':image' => $params['image'],
            ':is_enabled' => $params['is_enabled']
        ]);
    }

    public function addProduct($params)
    {
        $sth = $this->pdo->prepare("INSERT INTO `products`(`SKU`, `Qty`, `Name`, `Description`, `Price`, `Image`, `is_enabled`)
                                                                    VALUES (:SKU, :qty, :name_product, :description, :price, :image, :is_enabled)");
        $sth->execute([
            ':SKU' => $params['SKU'],
            ':qty' => $params['qty'],
            ':name_product' => $params['name'],
            ':description' => $params['description'],
            ':price' => $params['price'],
            ':image' => $params['image'],
            ':is_enabled' => $params['is_enabled']
        ]);
    }

    public function enableItem($enable, $id)
    {
        $sth = $this->pdo->prepare("UPDATE `products` SET `is_enabled` = :enable WHERE `Id` = :id");
        $sth->execute([':enable' => $enable, ':id' => $id]);
    }

    public function deleteItem($id)
    {
        $sth = $this->pdo->prepare("DELETE FROM `products` WHERE `Id` = :id");
        $sth->execute([':id' => $id]);
    }
}