<?php

namespace App\Services;

class Paypal
{

    private $apiEndpoint;
    private $subject = '';
    private $authToken = '';
    private $authSignature = '';
    private $authTimestamp = '';
    private $useProxy = FALSE;
    private $proxyHost = '127.0.0.1';
    private $proxyPort = 808;
    private $version = '65.1';
    private $apiUsername;
    private $apiPassword;
    private $apiSignature;


    public function __construct($config = array()){

        $apiEndPoint        = strtolower(env('PAYPAL_MODE')) == 'live' ? 'https://api-3t.paypal.com/nvp' : 'https://api-3t.sandbox.paypal.com/nvp';

        $this->apiEndpoint  = $apiEndPoint;

        // Setting up Credentials
        $this->apiUsername = env('PAYPAL_API_USERNAME');
        $this->apiPassword = env('PAYPAL_API_PASSWORD');
        $this->apiSignature = env('PAYPAL_API_SIGNATURE');

        //ob_start();
        //session_start();

        //if (count($config) > 0){
        //    foreach ($config as $key => $val){
        //        if (isset($key) && $key == 'live' && $val == 1){
        //            $this->apiEndpoint = 'https://api-3t.paypal.com/nvp';
        //            $this->paypalURL = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
        //        }else if (isset($this->$key)){
        //            $this->$key = $val;
        //        }
        //    }
        //}
    }
    public function nvpHeader(){
        $nvpHeaderStr = "";

        if((!empty($this->apiUsername)) && (!empty($this->apiPassword)) && (!empty($this->apiSignature)) && (!empty($subject))) {
            $authMode = "THIRDPARTY";
        }else if((!empty($this->apiUsername)) && (!empty($this->apiPassword)) && (!empty($this->apiSignature))) {
            $authMode = "3TOKEN";
        }elseif (!empty($this->authToken) && !empty($this->authSignature) && !empty($this->authTimestamp)) {
            $authMode = "PERMISSION";
        }elseif(!empty($subject)) {
            $authMode = "FIRSTPARTY";
        }
        
        switch($authMode) {
            case "3TOKEN" : 
                $nvpHeaderStr = "&PWD=".urlencode($this->apiPassword)."&USER=".urlencode($this->apiUsername)."&SIGNATURE=".urlencode($this->apiSignature);
                break;
            case "FIRSTPARTY" :
                $nvpHeaderStr = "&SUBJECT=".urlencode($this->subject);
                break;
            case "THIRDPARTY" :
                $nvpHeaderStr = "&PWD=".urlencode($this->apiPassword)."&USER=".urlencode($this->apiUsername)."&SIGNATURE=".urlencode($this->apiSignature)."&SUBJECT=".urlencode($subject);
                break;		
            case "PERMISSION" :
                $nvpHeaderStr = $this->formAutorization($this->authToken,$this->authSignature,$this->authTimestamp);
                break;
        }
        return $nvpHeaderStr;
    }
    
    /**
      * hashCall: Function to perform the API call to PayPal using API signature
      * @methodName is name of API  method.
      * @nvpStr is nvp string.
      * returns an associtive array containing the response from the server.
    */
    public function hashCall($methodName,$nvpStr){
        // form header string
        $nvpheader = $this->nvpHeader();

        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->apiEndpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
    
        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //in case of permission APIs send headers as HTTPheders
        if(!empty($this->authToken) && !empty($this->authSignature) && !empty($this->authTimestamp))
         {
            $headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        else 
        {
            $nvpStr = $nvpheader.$nvpStr;
        }

        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
       //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
        if($this->useProxy)
            curl_setopt ($ch, CURLOPT_PROXY, $this->proxyHost.":".$this->proxyPort); 
    
        //check if version is included in $nvpStr else include the version.
        if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
            $nvpStr = "&VERSION=" . urlencode($this->version) . $nvpStr;	
        }
        
        $nvpreq="METHOD=".urlencode($methodName).$nvpStr;
        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
    
        //getting response from server
        $response = curl_exec($ch);
        
        //convrting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray']=$nvpReqArray;
    
        if (curl_errno($ch)) {
            die("CURL send a error during perform operation: ".curl_error($ch));
        } else {
            //closing the curl
            curl_close($ch);
        }
    
        return $nvpResArray;
    }
    
    /** This function will take NVPString and convert it to an Associative Array and it will decode the response.
     * It is usefull to search for a particular key and displaying arrays.
     * @nvpstr is NVPString.
     * @nvpArray is Associative Array.
     */
    public function deformatNVP($nvpstr){
        $intial=0;
        $nvpArray = array();
    
        while(strlen($nvpstr)){
            //postion of Key
            $keypos = strpos($nvpstr,'=');
            //position of value
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
    
            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval = substr($nvpstr,$intial,$keypos);
            $valval = substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr = substr($nvpstr,$valuepos+1,strlen($nvpstr));
         }
        return $nvpArray;
    }
    
    public function formAutorization($auth_token,$auth_signature,$auth_timestamp){
        $authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
        return $authString;
    }
    
    public function paypalCall($params){
        /*
         * Construct the request string that will be sent to PayPal.
         * The variable $nvpstr contains all the variables and is a
         * name value pair string with & as a delimiter
         */
        
        //payment details item fields
        $itemStr = !empty($params['itemName'])?'&L_NAMEn='.$params['itemName']:'';
        $itemStr .= !empty($params['itemNumber'])?'&L_NUMBERn='.$params['itemNumber']:'';
        
        //indicate a recurring transaction
		$recurringStr = (array_key_exists("recurring",$params) && $params['recurring'] == 'Y')?'&RECURRING=Y':'';
        
        $nvpstr = "&PAYMENTACTION=".$params['paymentAction']."&AMT=".$params['amount']."&ACCT=".$params['creditCardNumber']."&EXPDATE=".$params['expMonth'].$params['expYear']."&CVV2=".$params['cvv']."&FIRSTNAME=".$params['firstName']."&LASTNAME=".$params['lastName']."&CURRENCYCODE=".$params['currency']."&COUNTRYCODE=".$params['countryCode']."&INVNUM=".$params['INVNUM'].$itemStr.$recurringStr;
    
        /* Make the API call to PayPal, using API signature.
           The API response is stored in an associative array called $resArray */
        $resArray = $this->hashCall("DoDirectPayment",$nvpstr);
    
        return $resArray;
    }

    public static function executePaypalCreditCard( $payment_data, $ip_address ) {

        $paypalParams = [

            'paymentAction'     => 'Sale',
            'amount'            => $payment_data['amount'],
            'creditCardNumber'  => $payment_data['cardnumber'],
            'expMonth'          => $payment_data['exp_month'],
            'expYear'           => $payment_data['exp_year'],
            'cvv'               => $payment_data['cvv'],
            'firstName'         => $payment_data['first_name'],
            'lastName'          => $payment_data['last_name'],
            'countryCode'       => env('PAYPAL_COUNTRY_CODE'),
            'currency'          => $payment_data['currency'],
            'ipaddress'         => $ip_address,
            'INVNUM'            => str_random(20)
        ];

//        return $payment_data;

        return (new self)->paypalCall( $paypalParams );
    }
}

