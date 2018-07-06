<?php

use App\Models\Fee;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds for adding fees.
     *
     * @return void
     */
    public function run()
    {

        if (Fee::where('key', 'withdraw')->first() == null) {
            Fee::create([
                'key' => 'withdraw',
                'value' => '.5',
                'description' => "% of fees for withdraw, value field value is in %"
            ]);
        }

        if (Fee::where('key', 'exchange')->first() == null) {
            Fee::create([
                'key' => 'exchange',
                'value' => '.2',
                'description' => "% of fees for exchange, value field value is in %"
            ]);
        }
    }
}
