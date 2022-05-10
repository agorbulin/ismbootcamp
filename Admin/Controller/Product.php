<?php

namespace Admin\Controller;

use Admin\Model\Product as ProductModel;
use Application;

class Product extends Base
{
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new ProductModel();
    }

    public function listAction()
    {
        $listProduct = $this->productModel->getList();

        Application::render('product/list.phtml', [
            'listProduct' => $listProduct,
            'title' => 'admin list product'
        ], 'Admin');
    }

    public function editAction($id)
    {
        if (!$id) {
            Application::render('product/edit-product.phtml', [
                'title' => 'add product',
                'heading' => 'Add',
                'noActive' => 'noActive'
            ], 'Admin');
        } else {
            $productItem = $this->productModel->getItem($id);
            Application::render('product/edit-product.phtml', [
                'productItem' => $productItem,
                'title' => 'edit product',
                'heading' => 'Edit',
                'noActive' => ''
            ], 'Admin');
        }
    }

    public function enableAction($id)
    {
        $post = $this->request->getParams();
        $enabled = $post['is_enabled'] == 0 ? 1 : 0;
        $this->productModel->enableItem($enabled, $id);
        header("location: /admin/product/edit/id/$id");
    }

    public function saveAction($id)
    {
        $params = $this->request->getParams();
        if ($_FILES['image']['name']) {
            $params['image'] = '/media/images/' . htmlspecialchars($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_DIR . '/pub' . $params['image']);
        }
        if ($id) {
            $params['id'] = htmlspecialchars($id);
            $params['is_enabled'] = 1;
            $this->productModel->updateProduct($params);
            header("location: /admin/product/edit/id/" . $params['id']);
        } else {
            $params['is_enabled'] = $params['is_enabled'] ?? 1;
            $this->productModel->addProduct($params);
            header("location: /admin");
        }
    }

    public function deleteAction($id)
    {
        $this->productModel->deleteItem($id);
        header("location: /admin");
    }
}