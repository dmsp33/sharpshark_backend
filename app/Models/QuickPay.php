<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class QuickPay
{
    public $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Config::get('services.quickpay.api_url'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    private function buildProductData($amount, $currency = 'USD')
    {
        return json_encode([
            "product_name" => "SharpShark",
            "quantity"  => 1,
            "price"     => $amount,
            "currency"  => $currency
        ]);
    }
    private function buildOrder(User $customer, $amount, $currency = 'USD') {
        return [
            "quantity"      => 1,
            "currency_symbol" => $currency,
            "customer_email" => $customer->email,
            "customer_name" => $customer->name,
            "tx_id"         => "uuid()",
            "order_id"      => (string) Str::orderedUuid(),
            "products_data" => $this->buildProductData($amount),
            "merchant_id"   => Config::get('services.quickpay.api_key'),
            "site_url"      => Config::get('services.quickpay.site_url'),
            "redirect"      => Config::get('services.quickpay.redirect'),
        ];
    }

    private function buildPaymentData($order, $amount) {
        return [
            "amount" => $amount,
            "currency" => $order['currency_symbol'],
            "customer_email" => $order['customer_email'],
            "customer_name" => $order['customer_name'],
            "order_id"      => $order['order_id'],
            "products_data" => $order['products_data'],
            "merchant"   => Config::get('services.quickpay.api_key'),
            "site_url"      => Config::get('services.quickpay.site_url'),
            "redirect"      => Config::get('services.quickpay.redirect'),
        ];
    }

    public function createOrder(User $customer, $amount)
    {
        if($amount < 100) {
            return null;
        }

        try {
            $order = $this->buildOrder($customer, $amount);
            $res = $this->client->request('POST', 'fiat-order', [
                'body' => json_encode($order)
            ]);
            $res->getBody();
            return $order;
        } catch (Exception $e) {
            throw new Exception('Error al crear orden'.$e);
        }
    }

    public function createPayment($order, $amount)
    {
        try {
            $res = $this->client->request('POST', 'payment', [
                'body' => json_encode($this->buildPaymentData($order, $amount))
            ]);
            $data = $res->getBody()->read(1024);
            
            return json_decode($data, true);
        } catch (Exception $e) {
            throw new Exception('Error al crear pago'.$e);
        }
    }

    public function charge(User $user, $amount)
    {
        $order = $this->createOrder($user, $amount);
        $payment_url = $this->createPayment($order, $amount);

        return $payment_url;
    }
}
