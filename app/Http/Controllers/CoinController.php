<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Transformers\CoinMarketInfoTransformer;
use App\Transformers\CoinTransformer;
use Illuminate\Http\Request;
use App\Models\Coin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;


class CoinController extends Controller
{

    public function coinIDByName( Request $request ) {

        $coin_id    = Coin::getCoinIDByName( $request->input( 'coin_coin' ) );
        return response()->json($coin_id, 200);
    }

    /**
     * Returns the coin and market data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function marketDataDisplay(Request $request)
    {
        $paramCoin       = $request->input('coin_coin');
        $stored_data    = [];

        $coins      = [];
        $coin       = Coin::getIDAndSymbol();

        foreach ($coin as $key => $value) {

            $keySymbol              = ($value['symbol'] == 'NEM') ? 'XEM' : $value['symbol'];
            $coins[$keySymbol]      = [
                $value->coin_id,
                $value->coin_title,
                $value->wallet_enabled
                ];
        }

        $keys   = [];
        $output = '';
        $limit  = count($coins);

        $index_limit    = $limit > 10 ? 10 : $limit;

        foreach ($coins as $key_coin => $coin_value) {

            $keys[]     = $key_coin;

            if( count($keys) == $index_limit ) {

                $symbols    = implode(',', $keys);
                $keys       = [];

                $limit  -= 10;
                $index_limit    = $limit > 10 ? 10 : $limit;

                $weight_avg = Transaction::getWeightAverage();

                $url        = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=" . $symbols . "&tsyms=" . $paramCoin;

                $ch = curl_init();
                // set url
                curl_setopt($ch, CURLOPT_URL, $url);
                //return the transfer as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // $output contains the output string

                $result = curl_exec($ch);

                if(isset(json_decode($result)->RAW)) {

                    $output = json_decode($result)->RAW;
                }

                // close curl resource to free up system resources
                curl_close($ch);


                foreach($output as $key => $value) {

                    $symbolData     = $value->$paramCoin;

                    if( isset($weight_avg[$symbolData->FROMSYMBOL]) ) {

                        $wt_avg     = $weight_avg[$symbolData->FROMSYMBOL];
                        $set_price  = $wt_avg / 1000;

                        $buy_price  = doubleval($weight_avg) + doubleval($set_price);
                        $sell_price = doubleval($weight_avg) - doubleval($set_price);
                    } elseif($symbolData->FROMSYMBOL === 'BTC') {

                        $buy_price  = $sell_price   = $symbolData->PRICE;
                    } else {

                        $set_price  =   doubleval($symbolData->PRICE) / 100;
                        $buy_price  = doubleval($symbolData->PRICE) + doubleval($set_price);
                        $sell_price = doubleval($symbolData->PRICE) - doubleval($set_price);
                    }

                    if($symbolData->FROMSYMBOL != $paramCoin) {

                        $stored_data[$symbolData->FROMSYMBOL]  = [
                            'coin_id'           => $coins[$symbolData->FROMSYMBOL][0],
                            'symbol'            => $symbolData->FROMSYMBOL,
                            'coin_title'        => $coins[$symbolData->FROMSYMBOL][1],
                            'change'            => number_format($symbolData->CHANGEPCT24HOUR, 2),
                            'price'             => $paramCoin === 'USD' ? number_format($symbolData->PRICE, 2) : number_format($symbolData->PRICE, 9),
                            'wallet_enabled'    => $coins[$symbolData->FROMSYMBOL][2],
                            'sell'              => $paramCoin === 'USD' ? number_format($sell_price, 2) :number_format($sell_price, 9),
                            'buy'               => $paramCoin === 'USD' ? number_format($buy_price, 2) : number_format($buy_price, 9),
                            'high'              => $paramCoin === 'USD' ? number_format($symbolData->HIGH24HOUR, 2) : number_format($symbolData->HIGH24HOUR, 9),
                            'low'               => $paramCoin === 'USD' ? number_format($symbolData->LOW24HOUR, 2) : number_format($symbolData->LOW24HOUR, 9)
                        ];
                    }
                }
            }
        }

        //Getting NEXT data
        DB::unprepared("CALL next_data(@next_data)");

        $result = DB::select('SELECT @next_data AS return_data');
        $result = json_decode($result[0]->return_data);

        $inserAt    = count($stored_data) - 1;

        $result->price   = $paramCoin === 'USD' ? number_format($result->price, 2) : number_format($result->price, 9);
        $result->sell   = $paramCoin === 'USD' ? number_format($result->sell, 2) : number_format($result->sell, 9);
        $result->buy   = $paramCoin === 'USD' ? number_format($result->buy, 2) : number_format($result->buy, 9);
        $result->high   = $paramCoin === 'USD' ? number_format($result->high, 2) : number_format($result->high, 9);
        $result->low   = $paramCoin === 'USD' ? number_format($result->low, 2) : number_format($result->low, 9);

        //Inserting NEXT values to display array
        $stored_data = array_slice($stored_data, 0, $inserAt, true) +
            ['NEXT' => (array)$result] +
            array_slice($stored_data, $inserAt, $inserAt-1, true) ;

        return response()->json(['data'=>$stored_data, 'count' => count($coins), 'count2' => count($stored_data)],200);
	}



    public function marketPerCoinDataDisplay(Request $request)
    {
        $paramCoin       = $request->input('coin_coin');
        $symbol       = $request->input('coin');
        $stored_data    = [];
        $coins       = Coin::getCoinDetailsByName($symbol);

        $weight_avg = Transaction::getWeightAverage();

        $url        = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=" . $symbol . "&tsyms=" . $paramCoin;

        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string

        $result = curl_exec($ch);

        if(isset(json_decode($result)->RAW)) {

            $output = json_decode($result)->RAW;
        }

        // close curl resource to free up system resources
        curl_close($ch);

        foreach($output as $key => $value) {

            $symbolData     = $value->$paramCoin;

            if( isset($weight_avg[$symbolData->FROMSYMBOL]) ) {

                $wt_avg     = $weight_avg[$symbolData->FROMSYMBOL];
                $set_price  = $wt_avg / 1000;

                $buy_price  = doubleval($weight_avg) + doubleval($set_price);
                $sell_price = doubleval($weight_avg) - doubleval($set_price);
            } elseif($symbolData->FROMSYMBOL === 'BTC') {

                $buy_price  = $sell_price   = $symbolData->PRICE;
            } else {

                $set_price  =   doubleval($symbolData->PRICE) / 100;
                $buy_price  = doubleval($symbolData->PRICE) + doubleval($set_price);
                $sell_price = doubleval($symbolData->PRICE) - doubleval($set_price);
            }

            $stored_data  = [
                'coin_id'           => $coins->coin_id,
                'symbol'            => $symbolData->FROMSYMBOL,
                'coin_title'        => $coins->coin_title,
                'change'            => number_format($symbolData->CHANGEPCT24HOUR, 2),
                'price'             => $paramCoin === 'USD' ? number_format($symbolData->PRICE, 2) : number_format($symbolData->PRICE, 2),
                'wallet_enabled'    => $coins->wallet_enabled,
                'sell'              => $paramCoin === 'USD' ? number_format($sell_price, 2) :number_format($sell_price, 9),
                'buy'               => $paramCoin === 'USD' ? number_format($buy_price, 2) : number_format($buy_price, 9),
                'high'              => $paramCoin === 'USD' ? number_format($symbolData->HIGH24HOUR, 2) : number_format($symbolData->HIGH24HOUR, 9),
                'low'               => $paramCoin === 'USD' ? number_format($symbolData->LOW24HOUR, 2) : number_format($symbolData->LOW24HOUR, 9),
                'supply'            => number_format($symbolData->SUPPLY, 2),
                'marketCap'         => number_format($symbolData->MKTCAP,2),
                'volume'            => number_format($symbolData->TOTALVOLUME24H, 2),
                'toSymbol'          => $symbolData->TOSYMBOL, 2
            ];

        }

        return response()->json(['data'=>$stored_data],200);
    }



	public function SaveCoindetails(Request $request)
	{
        $rules = array(
            'coinname' => 'unique:coins,coin_coin|required|min:3',
            'market' => 'required|min:3',
            'title' => 'unique:coins,coin_title|required',
            'description' => 'required',
        );
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $data = $validator->messages();
            $success=0;
        }
        else{
            $coin=new Coin();
            $coin->coin_coin=$request->get('coinname');
            $coin->coin_description=$request->get('market');
            $coin->coin_title=$request->get('title');
            $coin->coin_market=$request->get('description');
            $coin->coin_enabled=1;
            $coin->created_at=\Carbon\Carbon::now();
            $coin->updated_at=\Carbon\Carbon::now();
            $coin->save();
            $id=$coin->id;
            $coin=Coin::where('coin_id',$id)->first();
          //  event(new MessagePosted($coin));
            $data[]='New Coin  Created';
            $success=1;
        }

        return response()->json(['data'=>$data,'success'=>$success]);


	}

	public function Storeimage(Request $request)
	{
	    // TODO: CAN BE DELETED!!!!
         $rules = array(
            'file' => 'image|mimes:png|max:2048'
        );
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $data = $validator->messages();
            $success=0;
        }
        else
        {
			$fullName=$request->file('file')->getClientOriginalName();
			$filename=explode('.',$fullName);
			$name=$filename[0];
            $data = $name.'.'.$request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(
                base_path() . '/public/img/coin/', $data);
            $success=1;
        }
        return response()->json(['data'=>$data,'status'=>$success],200);

	}

	public function uploadCoinImageForm() {
        return view('dev.coinupload');
    }

    public function uploadCoinImage(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'photo' => 'required|image|mimes:jpeg,bmp,png|max:2048'
        ]);

            if($request->hasFile('photo') && $request->file('photo')->isValid())
            {

                $image = $request->file('photo');
                $filename  = strtoupper($request->input('name')) . '.' . 'png';

                $path = public_path('img/coin/');

                Image::make($image->getRealPath())->resize(16, 16)->save($path.'16/'.$filename)->encode('png');
                Image::make($image->getRealPath())->resize(32, 32)->save($path.'32/'.$filename)->encode('png');;
                Image::make($image->getRealPath())->resize(64, 64)->save($path.'64/'.$filename)->encode('png');;
                Image::make($image->getRealPath())->resize(128, 128)->save($path.'128/'.$filename)->encode('png');;
                Image::make($image->getRealPath())->resize(256, 256)->save($path.'256/'.$filename)->encode('png');;
                Image::make($image->getRealPath())->resize(512, 512)->save($path.'512/'.$filename)->encode('png');;
                Image::make($image->getRealPath())->save($path.'ORIG/'.$filename)->encode('png');;

                return back()->with('success','Image of Coin '.$request->input('name').' Upload successfully');
            }
    }


	public function checkadmin()
	{
		$user =  Auth::user();
		return response()->json(['is_admin'=>1,'status'=>1],200);

	}

	public function viewCoin($id)
	{

		$coin=Coin::where('coin_id',$id)->first();
		if(!$coin)
		{
		$coin=array();
		}
		return response()->json(['data'=>$coin],200);
	}

	public function deleteCoin($id)
	{

		$coin=Coin::find($id);
		if($coin)
		{
		$coin->delete();
		$data=array('success'=>1);
		}
		else{

			$data=array('success'=>0);
		}
		return response()->json(['data'=>$data],200);
	}


	public function updateCoindetails(Request $request,$id)
	{

			$rules = array(
					'market' => 'required',
					'title' => 'required|unique:coins,coin_title',
					'description' => 'required',

			);
			$validator = Validator::make($request->all(),$rules);
			if ($validator->fails()) {
					$data = $validator->messages();
					$success=0;
			}
			else{
			$id=(int)$request->get('id');
		    $coin=Coin::where('coin_id',$id)->first();
            $coin->coin_coin=$request->get('coin');
            $coin->coin_description=$request->get('description');
            $coin->coin_title=$request->get('title');
            $coin->coin_market=$request->get('market');
            $coin->coin_enabled=1;
            $coin->created_at=\Carbon\Carbon::now();
            $coin->updated_at=\Carbon\Carbon::now();
            $coin->save();
            $data[]=' Coin  Updated';
            $success=1;
			}
         return response()->json(['data'=>$data,'success'=>$success]);


	}

    /**
     * @param Request $request
     * @return array
     */
	public function searchCoins(Request $request)
    {
        $this->validate($request, [
            'query'  => 'required'
        ]);

        $query = $request->get('query') . '%';

        $coins = Coin::where('coin_coin', 'like', $query)
            ->orWhere('coin_description', 'like', $query)
            ->where('coin_enabled', true)
            ->get();

        $labels = Config::get('app.transaction_coins', []);
        $transactionalAbles = Coin::whereIn('coin_coin', $labels)
            ->get();

        return [
            'coins' => transformModel($coins, new CoinTransformer()),
            'transactional_ables' => transformModel($transactionalAbles, new CoinTransformer())
        ];
    }

    /**
     * Fetches available coins
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function coinMarketData()
    {
        $coins =  Coin::where('coin_erc', '=', 20)
            ->where('coin_enabled', '=', 1)
            ->get();

        return transformModel($coins, new CoinMarketInfoTransformer())
            ->respond();
    }

}
