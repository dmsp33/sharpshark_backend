<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;

class BlockchainNFT
{
    public $client;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function send(string $net, string $ipfs)
    {
        try {
			$response = $this->client->request('POST', $this->createRouteFromNet($net, $ipfs), [
				'headers' => $this->headersToNet($net)
			]);

			$data = json_decode($response->getBody(), true);

			return $data['link'];
			
		} catch (Exception $e) {
			throw new Exception("Error al enviar a red $net");
		}
    }

    private function createRouteFromNet(string $net, string $ipfs)
    {
        switch ($net) {
            case 'symbol':
                return env("BLOCKCHAIN_SYMBOL_URL").$ipfs;
            
            case 'binance':
                $network = 'binsc';
                break;

            case 'ethereum':
                $network = 'eth';
                break;

            case 'polygon':
                $network = 'poly';
                break;

            default:
                throw new Exception('Metodo de red(Blockchain) no soportado');
        }
        return env("BLOCKCHAIN_BASE_URL").$network.'/nft/ipfs/'.$ipfs;
    }

    private function headersToNet(string $net)
    {
        $headers =  [
            'Accept'    => 'application/json',
            'Content'   => 'application/json',
        ];

        if($net == 'symbol') {
            $headers['x-api-key'] = env("BLOCKCHAIN_SYMBOL_KEY");
        }

        return $headers;
    }
}
