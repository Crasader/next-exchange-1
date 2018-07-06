<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use App\Cache\WalletUpdateLimiter;
use App\Services\Blockchainservice;
use Illuminate\Queue\SerializesModels;
use App\Transformers\WalletTransformer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\WalletUpdatedNotification;

class UpdateWalletBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $walletId;
    public $walletAddress;
    public $coinName;
    public $contractAddress;

    /**
     * UpdateWalletBallanceJob constructor.
     *
     * @param $walletId
     * @param $walletAddress
     * @param $contractAddress
     * @param $coinName
     */
    public function __construct($walletId, $walletAddress, $contractAddress, $coinName)
    {
        $this->coinName = $coinName;
        $this->walletId = $walletId;
        $this->walletAddress = $walletAddress;
        $this->contractAddress = $contractAddress;
    }

    /**
     * @param WalletUpdateLimiter $limiter
     */
    public function handle(WalletUpdateLimiter $limiter)
    {
        //TODO: Uncomment when sure that cache working properly
//        if ($limiter->isLocked($this->walletId)) {
//            return;
//        }

        $limiter->lock($this->walletId);

        $response = Blockchainservice::getCoinBalance(
            $this->coinName,
            $this->walletAddress,
            $this->contractAddress
        );


        if ($response['status']) {
            $totalAmount = bcadd(0, $response['data'], 9);

            /** @var Wallet $wallet */
            $wallet = Wallet::find($this->walletId);

            $wallet->amount = $totalAmount;
            $wallet->synced_at = now();
            $wallet->save();

            /** @var User $walletOwner */
            $walletOwner = $wallet->user;

            $data = transformModel($wallet, new WalletTransformer())->toArray();
            $walletOwner->notify(new WalletUpdatedNotification($data));
        }

        $limiter->release($this->walletId);
    }
}
