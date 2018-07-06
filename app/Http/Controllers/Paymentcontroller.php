<?php

namespace App\Http\Controllers;

use App\Events\Paypalmessage;
use App\Helpers\Helper;
use App\Models\Coin;
use App\Models\Wallet;
use App\Traits\CaptureIpTrait;
use App\Traits\WalletTrait;
use Illuminate\Http\Request;
use App\Services\Paypal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use URL;
use Redirect;
use Input;
use App\Models\Payments;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;


class Paymentcontroller extends Controller
{
    use WalletTrait;

    private $_api_context;

    public function __construct()
    {
        $client_id      = config('paypal.client_id');
        $secret_key     = config('paypal.secret');
        $setting        = config('paypal.settings');

        $this->_api_context = new ApiContext(new OAuthTokenCredential($client_id, $secret_key));
        $this->_api_context->setConfig($setting);
    }

    // show paypal form view
    public function showForm(Request $request)
    {
        return view('pay');
    }

    public function payWithPaypal(Request $request)
    {
        return view('payment.payment', compact('payment'));
    }

    public function paypal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10'
        ]);

        if ($validator->fails()) {

            return response()->json(['data' => $validator->messages(), 'success' => 0]);
        }

        \Debugbar::notice('Paypal executed');

        $currency       = $request->input('currency');
        $send_amount    = $request->input('amount');
        $coinID         = $request->input('coin_id');
        $userID         = Auth::id();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName('Amount to Add')// item name
        ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($send_amount); // unit price

        // add item to list
        $item_list = new ItemList();
        $item_list->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($request->get('amount'));

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Amount to Add');

        $redirect_urls = new RedirectUrls();
        // Specify return & cancel URL
        $redirect_urls->setReturnUrl(url('/paypalstatus'))
            ->setCancelUrl(url('/wallet'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));


        try {

            \Debugbar::notice('Paypal execution');

            $payment->create($this->_api_context);

            foreach ($payment->getLinks() as $link) {

                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                }
            }

            if (isset($redirect_url)) {
                $walletID  = $this->depositToWallet(
                    $userID,
                    $coinID,
                    $send_amount,
                    false,
                    'fiat',
                    $payment->getId()
                );

                \Debugbar::notice('Output of WalletID');
                \Debugbar::info($walletID);

                Session::put('paypal_ids', [
                    'wallet_id'     => $walletID['wallet_id'],
                    'payment_id'    => $payment->getId(),
                    'amount'        => $send_amount
                    ]
                );

                // redirect to paypal!!
                return response()->json(['paypal_url' => $redirect_url, 'success' => 1, 'txnid' => $payment->getId()]);

            } else {
                $msg = 'Unknown error occurred';
                return response()->json(['data' => $msg, 'success' => 2]);
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            Log::error(json_encode([
                'code'      => $ex->getCode(),
                'data'      => $ex->getData(),
                'message'   => $ex->getMessage()
            ]));
        }
    }

    // Paypal process payment after it is done
    public function getPaymentStatus(Request $request)
    {

        //Session::put('paypal_payment_id', 'PAY-8W2595289C6279109LKCBZ7I');
        $paypal_ids     = Session::get('paypal_ids');
        Session::forget('paypal_ids');

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {

            return redirect('/wallet');
            //$data=array('message'=>'Fund is not loaded','url'=>'/exchange#/wallet','status'=>0);
        }

        $payment = Payment::get($paypal_ids['payment_id'], $this->_api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));

        try {

            $result = $payment->execute($execution, $this->_api_context);

            if ($result->getState() == 'approved') { // payment made
                // If approved!
                $this->updateWallet($paypal_ids['wallet_id'], $paypal_ids['amount'], true, $paypal_ids['payment_id']);
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            Log::error($ex->getData());

//            event(new Paypalmessage($resdata));
            return redirect('/wallet');
        }

        return redirect('/wallet');
    }


    public function creditCardPayPal(Request $request)
    {
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'amount' => 'required|numeric',
            'cardnumber' => 'required|numeric',
            'exp_month' => 'required|numeric',
            'exp_year' => 'required|numeric',
            'amount' => 'required|numeric',
            'cvv' => 'required|min:3',

        );
        $validator = Validator::make($request->all(), $rules);

        $success    = 0;

        if ($validator->fails()) {

            $response = $validator->messages();
            $success = -1;

        } else {

            $response       = Paypal::executePaypalCreditCard( $request->all(), $this->getClientIp() );

            if ($response["ACK"] == "SUCCESS") {

                $walletID  = $this->depositToWallet(
                    Auth::id(),
                    $request->input('coin_id'),
                    $request->input('amount'),
                    false,
                    'fiat',
                    $response["TRANSACTIONID"],
                    1
                );

                $success    = $walletID['wallet_id'];
            } else {

                $i = 0;
                $data[] = 'credit card number is wrong';
                foreach ($response as $key => $value) {


                    if (isset($response['L_LONGMESSAGE' . $i])) {

                        $data[] = $response['L_LONGMESSAGE' . $i];
                    }
                    $i++;
                }
                $success = 0;
            }
        }

        return response()->json(['data' => $response, 'success' => $success]);
    }
}
