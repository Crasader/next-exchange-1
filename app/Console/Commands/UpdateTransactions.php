<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'NextExchange:updatetransactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the blockchain transactions in db';

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
        \App::make('App\Http\Controllers\TransactionController')->getTransactions();
        $fp = fopen("debug.txt", "a");
        fputs($fp, 'schedule run for transactions'."\n");
        fclose($fp);
    }
}
