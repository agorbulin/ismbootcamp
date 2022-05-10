<?php

namespace Frontend\Controller;

use Api\NovaPoshta\NovaPoshta;

use Frontend\Model\OrderItem;
use Frontend\Model\Order as OrderModel;
use Application;

class Checkout extends Cart
{
    const EMAIL_FROM = 'ism_bootcamp@gmail.com';
    public $orderItem;
    public $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderItem = new OrderItem();
        $this->orderModel = new OrderModel();
    }

    public function getWarehouseList()
    {
        $novaPoshta = new NovaPoshta();
        $warehouseList = $novaPoshta->getWarehouses();
        return $warehouseList;
    }

    public function indexAction()
    {
        $empty = 'is empty';
        $orderList = $this->orderItem->getOrderProductItems($this->cartId);
        $totalOrderList = $this->countTotalItem($orderList);
        $total = $this->countTotal($totalOrderList);
        if (!$warehouseList = $this->getWarehouseList()) {
            $warehouseList = '';
        }
        Application::render('checkout/index.phtml', [
            'orderList' => $orderList,
            'total' => $total,
            'empty' => $empty,
            'warehouseList' => $warehouseList,
            'title' => 'Checkout page'
        ]);
    }

    public function submitAction()
    {
        if ($this->request->getRequestMethod() === 'POST') {
            $params = $this->request->getParams();
        }
        if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            $this->session->setMessage('The email field is empty or data is not valid');
        } elseif (!$params['address'] && !isset($params['warehouse'])) {
            $this->session->setMessage('The address field is empty.');
        } else {
            $params['warehouse'] = $params['warehouse'] ?? '';
            $params['address'] .= PHP_EOL . $params['warehouse'];
            $visitor = $this->visitor->getVisitorId($this->session_id);
            $params['visitor_id'] = $visitor['id'];
            $params['order_id'] = $this->getOrderId($params);
            $orderProductData = $this->orderItem->getProuctData($this->cartId);

            if ($this->addOrderItem($orderProductData, $params['order_id'])) {
                $params['message'] = $this->setEmailBody($params, $orderProductData);
                $this->sendMail($params);
                $this->session->refreshSessionId();
                $this->successAction($params['order_id']);
                exit;
            } else {
                $this->session->setMessage('Something went wrong, please contact us');
            }
        }
        $this->indexAction();
        exit;
    }

    public function addOrderItem($data, $orderId)
    {
        $i = 0;
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $value) {
                if ($this->orderItem->addOrderItem($value, $orderId)) {
                    $i += 1;
                }
            }
            return count($data) == $i;
        }
    }

    public function getOrderId($data)
    {
        $order = $this->orderModel->getOrderId($data);
        return $order['id'];
    }

    public function successAction($orderId)
    {
        Application::render('checkout/thanks.phtml', [
            'order' => $orderId,
            'title' => 'Thank you page',
        ]);
    }

    public function sendMail($data)
    {
        $subject = 'Order ID: #' . $data['order_id'];
        $to = $data['email'];
        $message = $data['message'];
        $headers = array(
            'From' => self::EMAIL_FROM,
            'Reply-To' => self::EMAIL_FROM,
            'Content-type' => 'text/html; charset=utf-8',
            'X-Mailer' => 'PHP/' . phpversion()
        );
        mail($to, $subject, $message, $headers);
    }

    public function setEmailBody($data, $productlist)
    {
        $products = '';
        foreach ($productlist as $item) {
            $products .= "<tr><td> ProductID: {$item['product_id']}</td>";
            $products .= "<td> Qty: {$item['qty']}</td>";
            $products .= "<td> Price: {$item['Price']}</td></tr>";
        }
        $message = "<html>";
        $message .= "<body>";
        $message .= "<h3>Thank you for your order</h3>";
        $message .= "Your order ID: " . $data['order_id'];
        $message .= "<br />";
        $message .= "Total: " . $data['total'];
        $message .= "<br />";
        $message .= "Delivery address: " . $data['address'];
        $message .= "<br />";
        $message .= "Products: <table>" . $products . "</table>";
        $message .= "</body>";
        $message .= "</html>";
        return $message;
    }
}