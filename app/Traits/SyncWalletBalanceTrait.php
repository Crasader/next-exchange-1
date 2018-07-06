<?php

namespace App\Traits;

use App\Jobs\UpdateWalletBalanceJob;

trait SyncWalletBalanceTrait
{
    /**
     * Syncs wallet balances with node server
     * in order to verify and keep updated
     * available amount.
     *
     * @param $walletId
     * @param $walletAddress
     * @param $contractAddress
     * @param $coinName
     */
    public static function runSyncWalletBalance($walletId, $walletAddress, $contractAddress, $coinName)
    {
        $job = new UpdateWalletBalanceJob(
            $walletId,
            $walletAddress,
            $contractAddress,
            $coinName
        );

        dispatch($job);
    }

}
