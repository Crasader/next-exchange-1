<?php

namespace App\Http\Controllers\Blockchain\Ethereum\Methods;

use App\Http\Controllers\Blockchain\Ethereum\Types\Address;
use App\Http\Controllers\Blockchain\Ethereum\Types\Transaction;
use App\Http\Controllers\Blockchain\Ethereum\Types\TransactionHash;

class Personal extends AbstractMethods
{
    /**
     * @return Address[]
     */
    public function listAccounts(): array
    {
        $addresses = [];
        $response = $this->client->send(
            $this->client->request(67, 'personal_listAccounts', [])
        );

        if (!$response->getRpcResult()) {
            return $addresses;
        }
        foreach ($response->getRpcResult() as $address) {
            $addresses[] = new Address($address);
        }

        return $addresses;
    }

    public function newAccount(string $password): Address
    {

        $response = $this->client->send(
            $this->client->request(67, 'personal_newAccount', [$password])
        );

        dd($response);

        return new Address($response->getRpcResult());
    }

    public function unlockAccount(Address $address, string $password, int $duration): bool
    {
        $response = $this->client->send(
            $this->client->request(67, 'personal_unlockAccount', [$address->toString(), $password, $duration])
        );

        return $response->getRpcResult();
    }

    public function sendTransaction(Transaction $transaction, string $password): TransactionHash
    {
        $response = $this->client->send(
            $this->client->request(1, 'personal_sendTransaction', [$transaction->toArray(), $password])
        );

        return new TransactionHash($response->getRpcResult());

    }
}
