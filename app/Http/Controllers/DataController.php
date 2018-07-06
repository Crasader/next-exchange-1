<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\MarketCap;
use App\Services\Coinservice;
use App\Events\Coinupdate;
use Illuminate\Support\Facades\Redis;

class DataController extends Controller
{

    public function getAndUpdateData()
    {
        $time_now = time();
        $data = $this->getDataFile();
        //$service=New Coinservice();
        //$data=$service->getDetails();

        if (is_array($data) && isset($data['last_update']) && $time_now < $data['last_update'] + 600) {
            return $data;
        }

        return $this->saveData($this->getCoinMarketCap(), $this->getRates());
    }

    public function getDataFile(){
        $json_data = @file_get_contents(public_path().'/data.json');
        return json_decode($json_data, true);
    }

    public function saveData($cmc,$rates){

        $data = array(
            'last_update' => time(),
            'cmc' => $cmc,
            'rates' => $rates
        );

        foreach($cmc as $Row){
			$requestData = $Row;
			$requestData['cmc_id'] = isset($Row['id'])?$Row['id']:'';
			unset($requestData['id']);
			MarketCap::create($requestData);
		}
        @file_put_contents(public_path().'/data.json', json_encode($data));

//        $coinController = new CoinController();
//        $dataPublish    = $coinController->marketDataDisplay();
//
        // Publishing the activity. Client side listeners will read this.
        $redis  = Redis::connection();
        $redis->publish('market-view-update', json_encode(['event' => 'marketupdated', 'data' => $data]));

        return $data;
    }

    public function getCoinMarketCap()
    {
        $cmc_json = $this->requestGET('https://api.coinmarketcap.com/v1/ticker/');
        return is_string($cmc_json) ? json_decode($cmc_json, true) : array();
    }

    public function requestGET($url)
    {
        $ch = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function getRates()
    {
        $fixer_json = $this->requestGET('https://api.fixer.io/latest');
        if (is_string($fixer_json)) {
            $fixer_data = json_decode($fixer_json, true);
            $fixer_data['rates']['EUR'] = 1;
            return $fixer_data['rates'];
        }
        return array();
    }

    /*to get top 10 data */
    /* public function get_top_market_cap(){
       $json_data = @file_get_contents(public_path().'/data.json');
       $data = json_decode($json_data, true);
      return view('pages.market-cap',compact('data'));
    }  */
    /* public function send_ajax_data(){
        echo  $userID = $request['first_name'];
        echo  $userID = Input::POST('first_name');
    } */

    public function get_top_market_cap()
    {
        $json_data = @file_get_contents(public_path() . '/data.json');
        $data = json_decode($json_data, true);
        return view('pages.market-cap-ajax', compact('data'));

    }
    public function get_top_market_cap_block()
    {
        $json_data = @file_get_contents(public_path().'/data.json');
        $data = json_decode($json_data, true);
        return view('pages.market-cap-ajax-block',compact('data'));
    }


}
