<?php  

namespace App\Repositories\Blockchain;

interface BlockchainInterface
{
	public function send(string $ipfsHash);
}