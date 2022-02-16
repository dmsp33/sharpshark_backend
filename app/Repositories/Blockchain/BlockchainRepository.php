<?php

namespace App\Repositories\Blockchain;

use App\Repositories\Blockchain\EthereumRepository;
use App\Repositories\Blockchain\BinanceRepository;
use App\Repositories\Blockchain\PolygonRepository;
use Exception;

class BlockchainRepository
{
	public function sendToBlockchainByNet(string $type, string $ipfsHash) {
		switch ($type) {
			case 'ethereum':
				$repo = new EthereumRepository();
				break;

			case 'nem':
			case 'symbol':
				$repo = new SymbolRepository();
				break;

			case 'binance':
				$repo = new BinanceRepository();
				break;

			case 'polygon':
				$repo = new PolygonRepository();
				break;
							
			default:
				throw new Exception('Metodo de red(Blockchain) no soportado');
				break;
		}

		return $repo->send($ipfsHash);
	}
}