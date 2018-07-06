<?php

namespace App\Transformers;

use App\Models\Coin;
use League\Fractal\TransformerAbstract;

class CoinTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Coin $coin)
    {
        return [
            'id'    => $coin->coin_id,
            'name'  => $coin->coin_coin,
            'title' => $coin->coin_title,
            'coin_description'  => $coin->coin_description,
            'market'    => $coin->coin_market,
            'is_fiat'   => (bool) $coin->coin_fiat,

        ];
    }
}
