<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateExpiredAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'NextExchange:updateexpiredaddresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the expired addresses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App::make('App\Http\Controllers\WalletController')->updateExpiredAddresses(1);
    }
}
