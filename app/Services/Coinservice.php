<?php
namespace App\Services;
use App\Models\Coin;
use DB;

class Coinservice
{
    public function getDetails ()
    {
        // Do what you need
        
		$coin=DB::table('coins')
		->leftjoin('market_caps','coins.coin_coin','=','market_caps.symbol')
		->select('coins.*','market_caps.*')
		->get();
		foreach($coin as $uniquedata)
		{	
			$das[$uniquedata->coin_id]=$uniquedata;
			
		}
	
		foreach($das as $coin=>$coinall)
		{
			
			
			$coinname=$coinall->coin_title;
			$coin_id=$coinall->coin_id;
			$symbol=$coinall->coin_coin;
			$highprices=DB::select("SELECT MAX( transaction_amount) as highprice
							FROM  `transactions` 
							WHERE  `transaction_maincoin` = '$coin_id'
							AND updated_at  >= CURDATE( )- INTERVAL 1 
							DAY 
							GROUP BY transaction_maincoin");
							
		
			if(count($highprices) > 0){
				$high=number_format($highprices[0]->highprice,2);
				if(!$high){
					$high=0;
				}
			}
			else{
				$high=0;
				
			}
		    $lowprice=DB::select("SELECT CAST(Min(transaction_amount) as DECIMAL(16,12))as lowprice
								from transactions 
								 WHERE  `transaction_maincoin` = '$coin_id' and updated_at >= CURDATE( )- INTERVAL 1 DAY
								  group by `transaction_maincoin`");
								 
			
			

			if(count($lowprice) > 0){	
			$low=$lowprice[0]->lowprice;
			}
			else{
				$low=0;
			}
			$last=DB::select("SELECT transaction_amount
							FROM  `transactions` 
							WHERE  `transaction_maincoin` =  '$coin_id'
							AND updated_at  >= CURDATE( ) - INTERVAL 1 
							DAY 
							ORDER BY transaction_id DESC 
							LIMIT 0 , 1
							");
							
							
			if(count($last) > 0){
				$lastcost=$last[0]->transaction_amount;	
			}
			else{
				$lastcost=0;
				
			}	
				
			$first=DB::select("SELECT transaction_amount
							FROM  `transactions` 
							WHERE  `transaction_maincoin` =  '$coin_id'
							AND updated_at  >= CURDATE( ) - INTERVAL 1 
							DAY 
							ORDER BY transaction_id asc 
							LIMIT 0 , 1
							");
			if(count($first) > 0){
				$firstcost=$first[0]->transaction_amount;	
			}
			else{
				$firstcost=0;
				
			}	
			$weightavg=DB::select("SELECT SUM( transaction_maincoin_amount * transaction_amount ) / SUM( transaction_maincoin_amount ) as weightedvag 
									FROM  `transactions` 
									WHERE  `transaction_maincoin` =  '$coin_id'
									AND updated_at  >= CURDATE( ) - INTERVAL 1 
									DAY");
				


//			$wgavg=count($weightavg[0]); //Didn't find any use of this variable in this code so I am commenting this line - Anish

			if((is_object($weightavg))==true){
				$buyprice=$weightavg[0]->weightedvag;
				$setprice=($buyprice*1)/100;
				$upprice=round($buyprice+$setprice,2);
				$downprice=round($buyprice-$setprice,2);
			}
			else{
				 $coinall->coin_title;
				echo $upprice=$coinall->price_btc;
				$downprice=$coinall->price_btc;
			}
			
			
									
			if(empty($lastcost) or empty($firstcost))
			{

				$change=$coinall->percent_change_1h;
				if(is_null($change))
				{
					$change=0;
				}
			}
			else{
	
				$change=($lastcost+$firstcost)/$lastcost;
			}
			$coinid=$coinall->coin_id;

			$data[]=array('coin_id'=>$coinid,
							 'symbol'=>$symbol,
							 'coin_title'=>ucfirst($coinall->coin_title),
							 'change'=>number_format($change,2),
							 'sell'=>$downprice,
							 'Buy'=>$upprice,
							 'High'=>$high,
							 'Low'=>$low
							);
		}	
		
		

		return $data;
    }
}
