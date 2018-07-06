<?php

namespace App\Http\Controllers\Blockchain\Ethereum;

use App\Http\Controllers\Blockchain\Ethereum\Methods\Eth;
use App\Http\Controllers\Blockchain\Ethereum\Methods\Net;
use App\Http\Controllers\Blockchain\Ethereum\Methods\Personal;
use App\Http\Controllers\Blockchain\Ethereum\Methods\Shh;
use App\Http\Controllers\Blockchain\Ethereum\Methods\Web3;
use Graze\GuzzleHttp\JsonRpc\Client;

class EthereumClient
{
    private $client;
    private $methods = [];

    public function __construct(string $url)
    {
        $this->client = Client::factory($url);
        $this->methods = [
            'net' => new Net($this->client),
            'eth' => new Eth($this->client),
            'shh' => new Shh($this->client),
            'web3' => new Web3($this->client),
            'personal' => new Personal($this->client),
        ];
    }

    public function net(): Net
    {
        return $this->methods['net'];
    }

    public function web3(): Web3
    {
        return $this->methods['web3'];
    }

    public function shh(): Shh
    {
        return $this->methods['shh'];
    }

    public function eth(): Eth
    {
        return $this->methods['eth'];
    }

    public function personal(): Personal
    {
        return $this->methods['personal'];
    }
}
