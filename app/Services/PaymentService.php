<?php

namespace App\Services;
use GuzzleHttp\Client;

class PaymentService {


    public function initializeTransaction(array $data) : mixed
    {
        $url = '/transaction/initialize';
        $response = $this->hitPaystack('POST',$url,$data);
        return $response['authorization_url'];
    }

    public function verifyTransaction(string $ref) : mixed
     {

        return $this->hitPaystack('GET','/transaction/verify/'. $ref);

    }

    private function hitPaystack(string $method, string $url, mixed $data = '') : mixed 
    {
        $client = new Client();

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . config('payment.paystack_secret'),
                'Content-Type' => 'application/json'
            ],
            'json' => $data
        ];

        $url = config('payment.paystack_url') . $url;

        try {
            //code...
            $response = $client->request($method, $url, $options);
            $result = $response->getBody()->getContents();
            $formatedData = json_decode($result, true);

            if ($formatedData['status'] == true) {
                return $formatedData['data'];
            }
            return false;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
