<?php

namespace Frontend\Controller;

use Frontend\Model\Visitor;
use Frontend\Model\Cart as CartModel;
use Frontend\Model\CartItem;
use Frontend\Model\Product as ProductModel;


class Base
{
    protected $session;
    public $visitor;
    public $cartModel;
    public $cartItem;
    public $filterParams = [];
    public $productModel;
    public $session_id;
    public $request;

    public function __construct()
    {
        $this->session = new \Session();
        $this->visitor = new Visitor();
        $this->cartModel = new CartModel();
        $this->cartItem = new CartItem();
        $this->productModel = new ProductModel();
        $this->session_id = $this->session->getSessionId();
        $this->request = new \Request();
        $this->setVisitor();
    }

    public function setVisitor()
    {
        if (!$this->session->issetVisitorSession()) {
            $this->session->setVisitorSession($this->session_id);
            $this->visitor->addVisitor($this->session_id);
        }
    }

    public function getSort($sort)
    {
        switch ($sort) {
            case'PriceHiToLow':
                $this->filterParams['sort'] = 'Price';
                $this->filterParams['rank'] = 'DESC';
                break;
            case'NameAZ':
                $this->filterParams['sort'] = 'Name';
                $this->filterParams['rank'] = 'ASC';
                break;
            case'NameZA':
                $this->filterParams['sort'] = 'Name';
                $this->filterParams['rank'] = 'DESC';
                break;
            default:
                $this->filterParams['sort'] = 'Price';
                $this->filterParams['rank'] = 'ASC';
        }
    }
}
