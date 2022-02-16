<?php

namespace App\Repositories\Blockchain;

use Exception;
use GuzzleHttp\Client;

class BinanceRepository implements BlockchainInterface
{
	private $client;
	private $baseUrl;

	public function __construct()
	{
		$this->client = new Client();
		$this->baseUrl = env("BLOCKCHAIN_BINANCE_BASE_URL", "");
	}

	public function send(string $ipfsHash)
	{
		if (empty($this->baseUrl)) {
			throw new Exception("Binance blockchain is not configured yet");
		} else {

			try {
				$response = $this->client->request('POST', $this->baseUrl.$ipfsHash, [
					'headers' => [
						'Accept'     => 'application/json',
					]
				]);

				$data = json_decode($response->getBody(), true);

				/**
				 * Response
				 * {
				 *	  "ipfs_hash": "QmQGnsc4eenKoM614Lwnfjj8F9FCV275CiJkrqweUPmfpc",
				*	  "state": "queued_up",
				*	  "tx_hash": "0xcbbad81bbc9d8a02a9c6a007566cb955970cf90883640af319b9bc13b5d06f9a",
				*	  "link": "https://rinkeby.etherscan.io/tx/0xcbbad81bbc9d8a02a9c6a007566cb955970cf90883640af319b9bc13b5d06f9a"
				*	}
				*
				* para acceder a los campos de la respuesta
				* $data['tx_hash']
				*/

				return $data['link'];
				
			} catch (Exception $e) {
				throw new Exception('Error al enviar a red binance');
			}
		}	
	}

}