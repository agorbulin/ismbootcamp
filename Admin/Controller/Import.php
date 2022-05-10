<?php

namespace Admin\Controller;

use Admin\Model\CsvImport;
use Admin\Model\Product as ProductModel;

class Import extends Base
{
    private $productModel;
    private $importCsv;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new ProductModel();
        $this->importCsv = new CsvImport();
    }

    public function productAction()
    {
        try {
            $dataForImport = $this->importCsv->parserCsv();
            $this->import($dataForImport);
        } catch (\Exception $e) {
            \Log::logErrors($e->getMessage(), get_class($e), $e->getFile(), $e->getLine());
            $objController = new Product();
            $objController->listAction();
            die;
        }
        $this->importCsv->setMessage();
        header("location: /admin");
    }

    public function import($products)
    {
        foreach ($products as $product) {
            $this->productModel->saveProduct($product);
        }
    }
}
