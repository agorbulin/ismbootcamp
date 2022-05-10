<?php

namespace Frontend\Controller;

use Application;

class Product extends Base
{
    public function listAction()
    {
        $filterParams = $this->request->getParams();
        $this->filterParams['sort'] = 'Price';
        $this->filterParams['rank'] = 'ASC';
        if (isset($filterParams['sort'])) {
            $this->getSort($filterParams['sort']);
        }
        $page = $filterParams['page'] ?? 1;
        $total = $this->productModel->getCountProduct();
        $pagination = new Pagination($page, $total);
        $this->filterParams['start'] = $pagination->getStart();
        $listProduct = $this->productModel->getList($this->filterParams);

        Application::render('product/list.phtml', [
            'listProduct' => $listProduct,
            'title' => 'user list product',
            'pagination' => $pagination,
        ]);
    }

    public function viewAction($id)
    {
        $productItem = $this->productModel->getItem($id);
        Application::render('product/page.phtml', [
            'productItem' => $productItem,
            'title' => 'page product'
        ]);
    }
}