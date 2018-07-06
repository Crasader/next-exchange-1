<?php
namespace App\Services;

use App\Helpers\Helper;
use App\Models\Coin;
use GuzzleHttp\Client;
use App\Models\Addresses;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;

class Blockchainservice {
	
	private $nodeServer;

	private $_coinId;
	private $_coin;

	public function __construct( )
    {
        $this->nodeServer = env("COIN_NODE_SERVER", "http://localhost:3000");
    }

    /**
     * Returns the incoming transaction list
     *
     * @param $symbol
     * @param $address
     * @return array|mixed
     */
    public static function getTransactionList($symbol, $address){

        $uri    = null;

        try {

            $client = new Client();

            if(strlen($address) && strlen($symbol)){
                $uri = (new self)->nodeServer.'/transaction/list/'.$symbol.'/'.$address;

                $res = $client->request('GET', $uri);
                $result = json_decode($res->getBody()->getContents(), true);

                return $result;
            }
        } catch(GuzzleException $exception) {

            $result_error = [
                'status'    => false,
                'error'     => $exception->getMessage(),
                'uri'       => $uri
            ];

            Helper::LogError( $result_error, 'BlockChainService Transaction List Error');
            return $result_error;
        }
    }

    /**
     * Returns the Balance of coin based on address
     *
     * @param $coin_name
     * @param $coin_address
     * @param null $contract_address
     * @return array|mixed
     */
    public static function getCoinBalance( $coin_name, $coin_address, $contract_address = null ) {

        if(! strlen($contract_address) ) {

            $uri    = (new self)->nodeServer . '/wallet/balance/'.strtolower($coin_name).'/'.$coin_address;
        } else {

            $uri    = (new self)->nodeServer . '/wallet/balance/erc20/'.$coin_address.'?contract='.$contract_address;
        }

        try {

            $client = new Client(); //GuzzleHttp\Client

            $res = $client->request('GET', $uri);
            $result = json_decode($res->getBody()->getContents(), true);

            return $result;
        } catch(GuzzleException $exception) {

            $result_error = [
                'status'    => false,
                'coin_name' => $coin_name,
                'error'     => $exception->getMessage(),
                'uri'       => $uri
            ];

            Helper::LogError( json_encode($result_error), 'Get Balance from BlockChainService Error' );
            return $result_error;
        }

    }

    /**
     * Sets the coin for blockchain
     *
     * @param $coin_id
     */
    public function setCoin($coin_id)
    {

        $this->_coinId = $coin_id;
    }

    /**
     * Creates a new coin address based on coin
     *
     * @return array|bool
     */
	public function createAddress( )
	{
		$new_address    = [

		    'pk'        => null,
            'address'   => null
        ];

		$coin_name      = $this->_getCoinNameByID();

		if ($coin_name === '') {
		    
        	return false;
        }

        $uri        = $this->nodeServer . '/wallet/create/'.$coin_name;

        try {

            $client     = new Client(); //GuzzleHttp\Client

            $res        = $client->request('POST', $uri);
            $res_status = $res->getStatusCode();

            $result     = json_decode($res->getBody()->getContents(), true);

            if ($res_status == 200 && $result['status']) {

                $new_address['address'] = $result['data']['addr'];
                $new_address['pk'] = $result['data']['pk'];
            }

            return $new_address;
        } catch (GuzzleException $exception) {

            $result_error = [
                'status'    => false,
                'coin_id'   => $this->_coinId,
                'error'     => $exception->getMessage(),
                'uri'       => $uri
            ];

            Helper::LogError(json_encode($result_error), 'Generate Address BlockChain Error');
            return false;
        }
	}

    /**
     * returns the coin name or type (erc20)
     *
     * @return string
     */
    private function _getCoinNameByID()
    {

        return Coin::getNameOrTypeByID($this->_coinId);
    }

    /**
     * Transfer amount from one address to other
     *
     * @param $coin
     * @param $pk
     * @param $from
     * @param $to
     * @param $amount
     * @return bool|mixed
     */
	public function transfer($coin, $pk, $from, $to, $amount) {

        \Debugbar::info($amount);

        $uri = $this->nodeServer . '/transaction/create/'.$coin;

        $data = [

            'pk'        => $pk,
            'from'      => $from,
            'to'        => $to,
            'amount'    => $amount
        ];

        if($coin == 'erc20') {

            $data['contract'] = $this->_getSmartContractAddress();
        }

        try {

            $client = new Client(); //GuzzleHttp\Client

            $res        = $client->request('POST', $uri, ['form_params' => $data]);
            $res_status = $res->getStatusCode();

            \Debugbar::debug($data);

            $result     = json_decode($res->getBody()->getContents(), true);

            if ($res_status == 200) {

                return $result;
            } else {
                \Debugbar::debug($result);
                Helper::LogError(json_encode($result), 'Transaction BlockChain Error success');
                return false;
            }
        } catch (GuzzleException $exception) {

            $result_error = [
                'status'    => false,
                'coin'      => $coin,
                'error'     => $exception->getMessage(),
                'uri'       => $uri,
                'data'      => $data
            ];

            Helper::LogError(json_encode($result_error), 'Transaction BlockChain Error failed to connect');
            return false;
        }

	}

    /**
     * Returns the erc20 contract address
     *
     * @return string
     */
    private function _getSmartContractAddress()
    {

        return Coin::getContractAddress($this->_coinId);
    }

    /**
     * Returns the Fee value of each coin transaction
     *
     * @param $amount
     * @return array|bool|mixed
     */
    public function getFee($amount) {

        $uri    = null;

        try {

            $coin_name = $this->_getCoinNameByID();

            if ($coin_name == '') {
                return false;
            }

            $uri = $this->nodeServer . '/transaction/fee/'.$coin_name.'?amount='.$amount;

            $client = new Client(); //GuzzleHttp\Client


            $res = $client->request('GET', $uri);
            $result     = json_decode($res->getBody()->getContents(), true);

            return $result;
        } catch(GuzzleException $exception) {

            $result_error = [
                'status'    => false,
                'coin_id'   => $this->_coinId,
                'error'     => $exception->getMessage(),
                'uri'       => $uri
            ];

            Helper::LogError(json_encode($result_error), 'Transaction BlockChain Error');
            return $result_error;
        }
    }
}