<?php

use Illuminate\Database\Seeder;

use App\Models\Conversation;

class ConversationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Conversation::create([
            'name'  => 'NEXT',
            'type'  => Conversation::ONE_WAY,
            'is_global' => true,
            'image'     => '/img/next-exchange-logo-sm.png'
        ]);
    }
}
