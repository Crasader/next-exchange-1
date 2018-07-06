<?php

namespace App\Transformers;

use App\Models\Wallet;
use League\Fractal\TransformerAbstract;

class WalletTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Wallet $wallet)
    {
        return [
            'id' => $wallet->id,
            'amount' => $wallet->amount,
            'market' => $wallet->coin->coin_market,
            'coin_id' => $wallet->coin_id
        ];
    }
}
