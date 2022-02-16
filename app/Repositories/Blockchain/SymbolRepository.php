<?php

namespace App\Repositories\Blockchain;

use Exception;
use GuzzleHttp\Client;

class SymbolRepository implements BlockchainInterface
{
	private $client;
	private $baseUrl = 'http://blockchain.sharpshark.io:3000/symbol/transaction/';

	public function __construct()
	{
		$this->client = new Client();
	}

	public function send(string $ipfsHash)
	{
		try {
			$response = $this->client->request('POST', $this->baseUrl.$ipfsHash, [
				'headers' => [
					'Accept'     => 'application/json',
					'Content'     => 'application/json',
				]
			]);

			$data = json_decode($response->getBody(), true);

			return $data['link'];
			
		} catch (Exception $e) {
			throw new Exception('Error al enviar a red symbol');
		}
	}

}