<?php

use App\Models\ChatRoom;
use App\Models\Coin;
use Illuminate\Database\Seeder;

class ChatRoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coin::all()->each(function (Coin $coin) {
            ChatRoom::updateOrCreate(
                [
                    'title' => $coin->coin_title
                ],
                [
                    'name' => $coin->coin_coin
                ]
            );
        });
    }
}