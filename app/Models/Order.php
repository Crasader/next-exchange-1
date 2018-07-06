<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class Order extends Model
{
    use SoftDeletes;

    protected $primaryKey   = 'order_id';

    /**
     * Returns the corresponding order based on order_id.
     *
     * @param $order_id
     * @return bool
     */
    public static function getOrderByOrderID( $order_id ) {

        $order  = Order::where('order_id', $order_id)->first();

        if($order) return $order;

        return false;
    }

    /**
     * Fetching the unexecuted orders of current user
     *
     * @return mixed
     */
    public static function getOrderByUser()
    {
        $orders = Order::where('order_executed', 0)
            ->where('order_user_id', Auth::id())
            ->orderBy('order_market')
            ->orderBy('order_maincoin')
            ->get();

        return $orders;
    }

    /**
     * Fetch the executed orders by user_id.
     *
     * @param $user_id
     * @return mixed
     */
    public static function getTransactionsByUser( $user_id ) {

        $transactions   = Order::where('order_executed', 1)
            ->where('order_user_id', $user_id )
            ->orderBy('order_market')
            ->orderBy('order_maincoin')
            ->get();

        return $transactions;
    }

    /**
     * Delete an oder by odrder_id
     *
     * @param $order_id
     * @return string
     */
    public static function deleteOrderByID( $order_id )
    {
        try {
            $order  = Order::where( 'order_id', $order_id )
                ->delete();
        }
        catch ( Exception $e ) {
            return $e->getMessage();
        }

        return $order;
    }

    /**
     * Return orders based on main coin and market
     *
     * @param $market
     * @param $coin
     * @return bool
     */
    public static function getOrdersByCoins( $market, $coin )
    {
        $orders  = Order::select(
            'order_id',
            'order_user_id',
            'order_price',
            'order_amount',
            'order_total',
            'order_buysell',
            'order_executed',
            'order_exchange',
            'updated_at'
        )
            ->where( 'order_market', $market )
            ->where( 'order_maincoin', $coin )
            ->where( 'order_executed', 0)
            ->orderBy('order_price', 'asc')
            ->get();

        if($orders) {
            return $orders;
        }

        return false;
    }
}
