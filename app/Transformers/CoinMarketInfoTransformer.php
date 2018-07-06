<?php

namespace App\Transformers;

use App\Helpers\Helper;
use App\Models\Coin;
use League\Fractal\TransformerAbstract;

class CoinMarketInfoTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Coin $coin)
    {
        return [
            'name'      => $coin->coin_coin,
            'title'     => $coin->coin_title,
            'market'    => $coin->coin_market,
            'address'   => $coin->coin_address,
            'prices'    => [
                'usd'   => Helper::getCurrentPrice($coin->coin_coin, 'usd'),
                'btc'   => Helper::getCurrentPrice($coin->coin_coin, 'btc')
            ],
            'change'    => Helper::get24hVolume($coin->coin_coin, 'percentage'),
            'volume'    => Helper::get24hVolume($coin->coin_coin, 'volume')
        ];
    }
}
