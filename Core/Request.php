<?php

class Request
{
    public function getRequestMethod()
    {
        $requestMethod = 'GET';
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']) {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
        }
        return $requestMethod;
    }

    public function getParams()
    {
        if ($this->getRequestMethod() === 'POST') {
            $params = $this->getFilterParams($_POST);
        } else {
            $params = $this->getFilterParams($_GET);
        }
        return $params;
    }

    public function getFilterParams($input)
    {
        foreach ($input as $key => $value) {
            $input[$key] = $this->inputData($value);
        }
        return $input;
    }

    public function inputData($data)
    {
        $data = trim($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
