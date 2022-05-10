<?php

namespace Admin\Controller;

use Admin\Model\Order as OrderModel;
use Application;

class Order extends Base
{
    public $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
    }

    public function listAction()
    {
        $orderList = $this->orderModel->getOrders();        
        Application::render('order/list.phtml', [
            'orderList' => $orderList,            
            'title' => 'Admin order list',
        ], 'Admin');
    }

    public function viewAction($id)
    {
        $orderInfo = $this->orderModel->getOrderInfo($id);
        $orderItems = is_array($orderInfo) && count($orderInfo) > 0 ? $this->orderModel->getOrderItems($orderInfo['id']) :'';        
        Application::render('order/page.phtml', [
            'orderInfo' => $orderInfo,
            'orderItems' => $orderItems,
            'title' => 'Admin order page',
        ], 'Admin');
    }
}
