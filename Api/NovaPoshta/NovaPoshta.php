<?php

namespace Api\NovaPoshta;

class NovaPoshta
{
    const API_URI = 'http://api.novaposhta.ua/v2.0/json/';
    const API_KEY = '839ecb43d7480c0bb0902be8a23c3cd5';
    const CITY = 'Житомир';
    protected $api;

    public function __construct()
    {
        $this->init();
    }

    public function __destruct()
    {
        curl_close($this->api);
    }

    public function init()
    {
        $ch = curl_init(self::API_URI);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->api = $ch;
    }

    public function getWarehouses()
    {
        $data = [
            "modelName" => "Address",
            "calledMethod" => "getWarehouses",
            "methodProperties" => [
                "CityName" => self::CITY
            ],
            "apiKey" => self::API_KEY
        ];
        $request = json_encode($data);
        curl_setopt($this->api, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($this->api);
        $result = [];
        foreach ($this->processResponse($response) as $item) {
            $result[] = ['Description' => $item->Description];
        }

        return $result;
    }

    protected function processResponse($response)
    {
        $response = (json_decode($response));
        if (isset($response->errors[0])) {
            throw new \Exception('API error:' . $response->errors[0]);
        }
        return $response->data;
    }
}