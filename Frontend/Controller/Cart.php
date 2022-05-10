<?php

namespace Frontend\Controller;

use Application;

class Cart extends Base
{
    public $cartId;

    public function __construct()
    {
        parent::__construct();
        $this->cartId = $this->getCartId();
    }

    public function indexAction()
    {
        $empty = 'is empty';
        $total = '';
        $totalCartList = '';
        $cartList = $this->cartItem->getCartProductItems($this->cartId);
        if (is_array($cartList) && count($cartList) > 0) {
            $empty = '';
            $totalCartList = $this->calculateTotalPerItem($cartList);
            $total = $this->countTotal($totalCartList);
        }

        Application::render('cart/index.phtml', [
            'cartList' => $totalCartList,
            'empty' => $empty,
            'total' => $total,
            'title' => 'Cart list product'
        ]);
    }

    public function countTotal($param)
    {
        $total = 0;
        foreach ($param as $value) {
            $total += $value['totalItem'];
        }
        return $total;
    }

    public function calculateTotalPerItem($param)
    {
        foreach ($param as $key => $value) {
            $param[$key]['totalItem'] = $value['qty'] * $value['Price'];
        }
        return $param;
    }

    public function getCartId()
    {
        $visitor = $this->visitor->getVisitorId($this->session_id);
        $cart = $this->cartModel->getCartId($visitor['id']);
        return $cart['id'];
    }

    public function getQtyItem($params)
    {
        $cartItemData = $this->cartItem->getCartItemData($params);
        return $cartItemData;
    }

    public function addItemAction()
    {
        $message = "Item successfully added to the cart";
        try {
            $filterParams = null;
            if ($this->request->getRequestMethod() === 'POST') {
                $filterParams = $this->request->getParams();
            }
            if (isset($filterParams['qty']) && ctype_digit($filterParams['qty']) && $filterParams['qty'] > 0) {
                $filterParams['cart_id'] = $this->cartId;
                $cartItemData = $this->getQtyItem($filterParams);
                $qty = $cartItemData['qty'] ?? 0;
                $filterParams['qty'] = $qty + $filterParams['qty'];
                $this->cartItem->saveToCartItem($filterParams);
            } else {
                $message = "The Qty field should be a natural number";
            }
        } catch (\Exception $e) {
            \Log::logErrors($e->getMessage(), get_class($e), $e->getFile(), $e->getLine());
            $message = 'Item did not add to the cart';
        }
        $this->session->setMessage($message);
        header("location: /product/view/id/{$filterParams['product_id']}");
    }

    public function deleteItemAction($id)
    {
        $this->cartItem->deleteItem($id, $this->cartId);
        header("location: /cart");
    }
}
