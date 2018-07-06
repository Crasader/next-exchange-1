<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\AccessFormRequest;
use App\Models\Access;
use App\Models\Contact;
use App\Models\Ico;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\StoreIcoRequest;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Mail;

use Illuminate\Http\Request;

class FormController extends Controller
{

    public function showEarlyAccess() {
        return view('pages.earlyaccess');
    }

    public function storeEarlyAccess(AccessFormRequest $request)
    {
        // store form details in database.
        $request['user_id'] = Auth::id();
        Access::create($request->all());
        // Send form details in email.

        //return response()->json([
        //    'success' => true,
        //    'message' => 'Thanks for your request, our team will review the application and send after approval the invite link.'
        //]);
        return \Redirect::route('tokenholderForm')
            ->with('message', 'Thanks, we received the application. When approved you will get access to our Exchange. Stay tuned.');
    }


    public function store_contact(StoreContactRequest $request)
    {

       $contect =  Contact::where('email','=', $request->email)->where('created_at', '>=', \Carbon\Carbon::now()->subMinutes(120))->count();
     
        if($contect == 0){
            Contact::create($request->all());
            return redirect('/contact')->with(['message' => 'Thanks for your enquiry, we\'ll be in touch shortly.']);
        }else{

            return redirect('/contact')->with(['message' => 'Please send another query after 120 minits.']);
        }
            
    }
    
    public function store_ico(StoreIcoRequest $request)
    {
		
		$launch_date = $request->get('launch_date');
		$launch_date = date('Y-m-d',strtotime($launch_date));
		$requestData = $request->all();
		$requestData['launch_date'] = $launch_date;
		$requestData['initial_price'] = Helper::convertToFloat($request->get('initial_price'));
		// store form details in database.
        Ico::create($requestData);
        // Send form details in email.
        Mail::send('emails.ico',
            array(
                'name' => $request->get('name'),
                'symbol' => $request->get('symbol'),
                'total_supply_token' => $request->get('total_supply_token'),
                'stage' => $request->get('stage'),
                'launch_date' => $request->get('launch_date'),
                'initial_price' => $request->get('initial_price'),
                'short_description' => $request->get('short_description'),
                'full_description' => $request->get('full_description'),
                'website_url' => $request->get('website_url'),
                'whitepaper_url' => $request->get('whitepaper_url'),
                'twitter_url' => $request->get('twitter_url'),
                'facebook_url' => $request->get('facebook_url'),
                'telegram_url' => $request->get('telegram_url'),
                'bitcointalk_url' => $request->get('bitcointalk_url'),
                'official_video_url' => $request->get('official_video_url'),
            ), function($message)
            {
                $message->from('noreply@next.exchange'); // set the email-id of sender.
                $message->to('info@next.exchange', 'Admin')->subject('Ico Listing'); // Set the email-id of receiver
            });
            

		return response()->json([
					'success' => true,
					'message' => 'Thanks for Listing, we\'ll be in touch shortly.'
				]);
    }
}
