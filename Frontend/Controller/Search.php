<?php

namespace Frontend\Controller;

use Application;

class Search extends Base
{
    public function indexAction()
    {
        $filterParams = $this->request->getParams();
        if (isset($filterParams['searchQuery']) && $filterParams['searchQuery']) {
            $this->filterParams['searchQuery'] = $filterParams['searchQuery'];
            $this->filterParams['sort'] = 'Price';
            $this->filterParams['rank'] = 'ASC';
            if (isset($filterParams['sort'])) {
                $this->getSort($filterParams['sort']);
            }
            $page = $filterParams['page'] ?? 1;
            $total = $this->productModel->getSearchCountProduct($this->filterParams['searchQuery']);
            $pagination = new Pagination($page, $total);
            $this->filterParams['start'] = $pagination->getStart();
            $listProduct = $this->productModel->getListProductsFromSearch($this->filterParams);
        } else {
            $this->filterParams['searchQuery'] = '';
            $listProduct = '';
            $pagination = '';
        }
        if (empty($listProduct)) {
            $this->session->setMessage("product: '{$filterParams['searchQuery']}' not found");
        }

        Application::render('search/index.phtml', [
            'listProduct' => $listProduct,
            'title' => 'search list product',
            'searchQuery' => $this->filterParams['searchQuery'],
            'pagination' => $pagination,
        ]);
    }
}
