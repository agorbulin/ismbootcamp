<?php

namespace Frontend\Model;

class Product extends Base
{
    public function getList($filterParams)
    {
        $sort = $filterParams['sort'];
        $rank = $filterParams['rank'];
        $productsPerPage = COUNT_OF_PRODUCTS;
        $start = $filterParams['start'];
        $res = $this->pdo->query("SELECT `Id`, `SKU`, `Name`, `Description`, `Price`, `Image`, `is_enabled` 
                                            FROM `products` 
                                            WHERE `is_enabled` = 1 
                                            ORDER BY $sort  $rank 
                                            LIMIT $start, $productsPerPage ");
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getListProductsFromSearch($filterParams)
    {
        $searchQuery = $filterParams['searchQuery'];
        $sort = $filterParams['sort'];
        $rank = $filterParams['rank'];
        $start = $filterParams['start'];
        $productsPerPage = COUNT_OF_PRODUCTS;
        $sth = $this->pdo->prepare("SELECT `Id`, `SKU`, `Name`, `Description`, `Price`, `Image`, `is_enabled` 
                                          FROM `products` 
                                          WHERE `Name` LIKE '%$searchQuery%' AND `is_enabled` = 1 
                                          ORDER BY $sort  $rank 
                                          LIMIT $start, $productsPerPage ");
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSearchCountProduct($searchQuery)
    {
        $sth_count = $this->pdo->query("SELECT COUNT(*) AS total FROM `products` WHERE `Name` LIKE '%$searchQuery%' AND `is_enabled` = 1 ");
        $sth_count->execute();
        return $result = intval($sth_count->fetch()['total']);
    }

    public function getCountProduct()
    {
        $sth_count = $this->pdo->query("SELECT COUNT(*) AS total FROM `products` WHERE `is_enabled` = 1 ");
        $sth_count->execute();
        return $result = intval($sth_count->fetch()['total']);
    }

    public function getItem($id)
    {
        $sth = $this->pdo->prepare("SELECT `Id`, `SKU`, `Name`, `Description`, `Price`, `Image` FROM `products` WHERE `id` = ? ");
        $sth->execute([$id]);
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }
}