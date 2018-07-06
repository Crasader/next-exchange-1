<?php

use App\Models\Coin;
use Illuminate\Database\Seeder;

class CoinsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Add Coins
         *
         */

        if (Coin::where('coin_coin', '=', 'BTC')->first() === null) {
            Coin::create([
                'coin_coin' => 'BTC',
                'coin_description' => 'Bitcoin',
                'coin_title' => 'Bitcoin',
                'coin_market' => 'BTC',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'ETH')->first() === null) {
            Coin::create([
                'coin_coin' => 'ETH',
                'coin_description' => 'Ethereum',
                'coin_title' => 'Ethereum',
                'coin_market' => 'ETH',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'BCC')->first() === null) {
            Coin::create([
                'coin_coin' => 'BCC',
                'coin_description' => 'Bitcoin Cash',
                'coin_title' => 'Bitcoin Cash',
                'coin_market' => 'BCC',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'XRP')->first() === null) {
            Coin::create([
                'coin_coin' => 'XRP',
                'coin_description' => 'Ripple',
                'coin_title' => 'Ripple',
                'coin_market' => 'XRP',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'DASH')->first() === null) {
            Coin::create([
                'coin_coin' => 'DASH',
                'coin_description' => 'Dash',
                'coin_title' => 'Dash',
                'coin_market' => 'DASH',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'LTC')->first() === null) {
            Coin::create([
                'coin_coin' => 'LTC',
                'coin_description' => 'Litecoin',
                'coin_title' => 'Litecoin',
                'coin_market' => 'LTC',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'XMR')->first() === null) {
            Coin::create([
                'coin_coin' => 'XMR',
                'coin_description' => 'Monero',
                'coin_title' => 'Monero',
                'coin_market' => 'XMR',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'ADA')->first() === null) {
            Coin::create([
                'coin_coin' => 'ADA',
                'coin_description' => 'Cordano',
                'coin_title' => 'Cordano',
                'coin_market' => 'ADA',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'NEM')->first() === null) {
            Coin::create([
                'coin_coin' => 'NEM',
                'coin_description' => 'Nem',
                'coin_title' => 'Nem',
                'coin_market' => 'NEM',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'USD')->first() === null) {
            Coin::create([
                'coin_coin' => 'USD',
                'coin_description' => 'US Dollar',
                'coin_title' => 'US Dollar',
                'coin_market' => 'USD',
                'coin_enabled' => 1,
                'coin_fiat' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'EUR')->first() === null) {
            Coin::create([
                'coin_coin' => 'EUR',
                'coin_description' => 'Euro',
                'coin_title' => 'Euro',
                'coin_market' => 'EUR',
                'coin_enabled' => 1,
                'coin_fiat' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'NEXT')->first() === null) {
            $newCoin = Coin::create([
                'coin_coin' => 'NEXT',
                'coin_description' => 'NEXT',
                'coin_title' => 'Next',
                'coin_market' => 'ETH',
                'coin_enabled' => 1
            ]);
        }
        if (Coin::where('coin_coin', '=', 'ETN')->first() === null) {
            Coin::create([
                'coin_coin' => 'ETN',
                'coin_description' => 'Electroneum',
                'coin_title' => 'Electroneum',
                'coin_market' => 'ETN',
                'coin_enabled' => 1
            ]);
        }
    }
}