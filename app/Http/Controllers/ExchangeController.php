<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\Token;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Auth;

class ExchangeController extends Controller
{
    public function getDashboard()
    {
        return view('app.vue-dashboard', [
            'user_id' => Auth::id(),
            'user_disclaimer' => Auth::user()->user_disclaimer
        ]);
    }

    public function getTokens() {
        $coins =  Coin::where('coin_erc', '=', 20)
            ->where('coin_enabled', '=', 1)
            ->get();
        return view('exchange.index', ['coins' => $coins]);
    }

    public function getExchangeToken($symbol)
    {
        $coin =  Coin::where(['coin_coin' => $symbol])->first();
        return view('exchange.next' , ['coin' => $coin]);
    }

    public function getMarket()
    {
        return view('exchange.market');
    }

    public function getOrderbook()
    {
        return view('exchange.orderbook');
    }

    public function getWallet()
    {
        return view('exchange.wallet');
    }

    public function getExchangeBeta()
    {

        return view('exchange.beta');
    }

    /**
     * Returns the (currencies)coins with current balance
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchangewalletdetails()
    {

        $currencies            = Wallet::currencyCoins();
        $digital_currencies    = Coin::digitalCurrencies();

        return response()->json(['currencies'=>$currencies,'digital_currencies'=>$digital_currencies]);

    }

    /**
     * Returns the single coin value of $to coin by $from
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRates(Request $request)
    {
        $to     = $request->get('to') === 'NEM' ? 'XEM' : $request->get('to');
        $from   = $request->get('from');
        $price  = 0.00;

        if($to === 'NEXT')
        {

            $output     = $this->getCurrentMarket($from, 'ETH');
            $ETHPrice   = $output->ETH->$from->PRICE;

            //Price for one NEXT
            $price      = $this->getNEXTPrice( $ETHPrice, 'ETH');
        } else if($from == 'NEXT') {

            $output     = $this->getCurrentMarket('ETH', $to);
            $ETHPrice   = $output->$to->ETH->PRICE;

            $price      = $this->getNEXTPrice( $ETHPrice, 'ETH', true );

        } else {

            $output = $this->getCurrentMarket($from, $to);
            $price  = $output->$to->$from->PRICE;
        }

        $quotes = '{"'.$to.'":'.$price.'}';
        $names  = '{"name":"'.$from.'","quotes":'.$quotes.'}';
        $data   = '{"'.$from.'":'.$names.'}';

        return response()->json($data);
    }

    /**
     * Returns the market value of $from coin as $to coin
     *
     * @param $from
     * @param $to
     * @return mixed
     */
    private function getCurrentMarket($from, $to) {

        $ch         = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL,
            "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=" . $to . "&tsyms=" . $from);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output     = json_decode(curl_exec($ch))->RAW;
        // close curl resource to free up system resources
        curl_close($ch);

        return $output;
    }

    /**
     * Calculates the price of 1 NEXT for selected left side drop down.
     *
     * @param $ETHPrice //ETH value of the other coin
     * @param $c_c //Conversion coin
     * @param bool $fromNEXT //Checks NEXT is from or to Coin
     * @return float
     */
    private function getNEXTPrice($ETHPrice, $c_c, $fromNEXT = false)
    {

        $oneETH = env('ETH2NEXT');
        $oneNEXT = bcdiv(1, $oneETH, 9); //Price of one NEXT to ETH.

        $price = 0.00;

        if ($fromNEXT) {

            $price = $c_c == 'ETH' ? $oneETH : bcdiv($oneNEXT, $ETHPrice, 9);
        } else {

            $price = $c_c == 'ETH' ? $oneNEXT : bcdiv($ETHPrice, $oneNEXT, 9);
        }

        return $price;
    }

}