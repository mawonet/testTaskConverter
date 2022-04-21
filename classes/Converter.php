<?php


class Converter
{

    public $path = './config/currencyExchange.json';
    public $resultConvert = [
        "USD" => 0,
        "EUR" => 0,
        "RUB" => 0,
        "BYN" => 0,
    ];

    public function __construct()
    {
    }

    public function getInstance(): void
    {
        $this->checkRelevance();
    }

    private function checkRelevance(): void
    {
        $data = file_get_contents($this->path);
        $data = json_decode($data, true);
        if (!$data['timestamp'] || (date('Y-m-d', $data['timestamp']) != date('Y-m-d'))) {
            $this->getExchange();
        }
    }

    private function getExchange(): void
    {
        require_once './config/config.php';
        file_put_contents($this->path, '');
        $data = curl_init();
        curl_setopt($data, CURLOPT_URL, $config['url'] . $config['apiKey']);
        curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($data);
        curl_close($data);
        $response = json_decode($response, true);
        $result = [
            "timestamp" => $response["timestamp"],
            "USD" => $response["rates"]["USD"],
            "EUR" => $response["rates"]["EUR"],
            "RUB" => $response["rates"]["RUB"],
            "BYN" => $response["rates"]["BYN"]
        ];
        file_put_contents($this->path, json_encode($result));
    }

    public function convert($value, $name): array
    {
        $data = file_get_contents($this->path);
        $data = json_decode($data, true);
        if ($value > 0 && $data) {
            switch ($name) {
                case "USD":
                    $this->resultConvert["USD"] = $value;
                    $this->resultConvert["EUR"] = $value * $data["EUR"];
                    $this->resultConvert["RUB"] = $value * $data["RUB"];
                    $this->resultConvert["BYN"] = $value * $data["BYN"];
                    break;
                case "EUR":
                    $this->resultConvert["USD"] = $value * $data["USD"] / $data["EUR"];
                    $this->resultConvert["EUR"] = $value;
                    $this->resultConvert["RUB"] = $value * $data["RUB"] / $data["EUR"];
                    $this->resultConvert["BYN"] = $value * $data["BYN"] / $data["EUR"];
                    break;
                case "RUB":
                    $this->resultConvert["USD"] = $value * $data["USD"] / $data["RUB"];
                    $this->resultConvert["EUR"] = $value * $data["EUR"] / $data["RUB"];
                    $this->resultConvert["RUB"] = $value;
                    $this->resultConvert["BYN"] = $value * $data["BYN"] / $data["RUB"];
                    break;
                case "BYN":
                    $this->resultConvert["USD"] = $value * $data["USD"] / $data["BYN"];
                    $this->resultConvert["EUR"] = $value * $data["EUR"] / $data["BYN"];
                    $this->resultConvert["RUB"] = $value * $data["RUB"] / $data["BYN"];
                    $this->resultConvert["BYN"] = $value;
                    break;
            }
        }
        return $this->resultConvert;
    }

}