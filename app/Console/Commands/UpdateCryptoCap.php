<?php

namespace App\Console\Commands;

use App\Events\CryptoCapUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\DataController;

class UpdateCryptoCap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'NextExchange:updatecryptocap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the cryptocap';

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
        App::make(DataController::class)
            ->getAndUpdateData();

        event(new CryptoCapUpdated());
    }
}
