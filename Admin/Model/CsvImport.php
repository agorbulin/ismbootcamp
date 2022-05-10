<?php

namespace Admin\Model;

class CsvImport
{
    public const PRODUCTS_CSV = ROOT_DIR . '/var/products.csv';
    const CSV_SEP = ';';
    public $success = 0;
    public $allproducts = 0;

    public function __construct()
    {
        $this->session = new \Session();
    }

    public function parserCsv()
    {
        $productsCsv = self::PRODUCTS_CSV;
        if (!is_readable($productsCsv)) {
            $this->session->setMessage("Can not found the file");
            throw new \Exception("Can not found the $productsCsv file");
        }
        if (substr($productsCsv, strrpos($productsCsv, '.')) !== '.csv') {
            $this->session->setMessage("Wrong extension in the file");
            throw new \Exception("Wrong extension in $productsCsv file");
        }
        $handle = fopen($productsCsv, 'r');
        if (!$handle) {
            $this->session->setMessage("Can not read the file");
            throw new \Exception("Can not read the $productsCsv file");
        }
        $columnNames = fgetcsv($handle, 0, self::CSV_SEP, '"');

        while (($row = fgetcsv($handle, 0, self::CSV_SEP, '"')) !== false && $row) {
            $this->allproducts += 1;
            if (count($row) == count($columnNames)) {
                $dataToSetKey[] = array_combine($columnNames, $row);

            } else {
                \Log::logImport("values more than column names");
            }
        }
        fclose($handle);
        $dataToCheck = $this->setKeys($dataToSetKey);
        $dataToImport = $this->checkData($dataToCheck);
        return $dataToImport;
    }

    public function checkData($data)
    {
        foreach ($data as $value) {
            $error = null;
            if (empty($value['SKU'])) {
                $error .= "SKU empty in product with name={$value['name']}, price={$value['price']}  and qty={$value['qty']}";
            }
            if (empty($value['qty']) && !is_numeric($value['qty'])) {
                $error .= " qry empty or not number in product with SKU={$value['SKU']} ";
            }
            if (empty($value['price']) && !is_numeric($value['price'])) {
                $error .= " price empty or not number in product with SKU={$value['SKU']} ";
            }
            if (empty($value['name'])) {
                $error .= " name empty in product with SKU={$value['SKU']} ";
            }
            if (empty($value['is_enabled']) && !($value['is_enabled'] == 1 || $value['is_enabled'] == 0)) {
                $error .= " is_enabled empty or not number in product with SKU={$value['SKU']} ";
            }
            if (!$error) {
                $dataToImport[] = $value;
                $this->success++;
            } else {
                \Log::logImport($error);
            }
        }
        return $dataToImport;
    }

    public function setMessage()
    {
        $failed = $this->allproducts - $this->success;
        $this->session->setMessage("Data imported. Success : $this->success  Failed :  $failed");
    }

    public function setKeys($data)
    {
        $dataWithNewKeys = array_map(function ($params) {
            return array(
                'SKU' => $params['sku'],
                'qty' => $params['qty'],
                'name' => $params['name'],
                'description' => $params['description'],
                'price' => $params['price'],
                'image' => $params['image'],
                'is_enabled' => $params['enabled'],
            );
        }, $data);
        return $dataWithNewKeys;
    }
}
