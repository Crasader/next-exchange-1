<?php
namespace App\Http\Controllers;
use Event;
use App\Models\Coin;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\Coinupdate;
use Redish;
use Illuminate\Support\Facades\Hash;
use Response;
use DB;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Coinservice;

class MessagesController extends Controller {

	
	public function Coin(){
		
		
		
		$message=Coin::first();
	    event(new Coinupdate($message));
		return "Event fired";
		die();
	
	}
	
	
	
	public function all(Coinservice $service)
    {
		

		 $data=$service->getDetails();
		//$coin=DB::table('transactions')->where('transaction_id',1)->get();
		//$random=random_int(10,100);	
		event(new Coinupdate($data));
		return response()->json(['data'=>$data],200);
		
	}
}
