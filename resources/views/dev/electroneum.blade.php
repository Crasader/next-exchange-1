 @extends('_layouts.main')

 @section('content')

     @include('_partials.status-panel')

<?php
var_dump($electroneum);
?>
     <h1>Example of Monero Library</h1>
	<p>Welcome to Monero PHP and JSON Library, developed by SerHack! Please report any issue <a href="https://github.com/monero-integrations/monerophp/issues">here</a></p>
	<h2>Informations</h2>
    <h3>Monero Address</h3>
    <?php $new_addr = $electroneum->make_integrated_address('');
    var_dump($new_addr);
    ?>

    <?php $address = $electroneum->address();
	$electroneum->_print($address); ?>
    <h3>Balance</h3>
    <?php $balance = $electroneum->getbalance();
	 $electroneum->_print($balance); ?>
	<h3>Height</h3>
	<?php $height = $electroneum->getheight();
		$electroneum->_print($height); ?>
	<h3>Incoming transfers</h3>
	<h4>All</h4>
	<?php $incoming_transfers = $electroneum->incoming_transfer('all');
		$electroneum->_print($incoming_transfers); ?>
	<h4>Available</h4>
	<?php $available = $electroneum->incoming_transfer('available');
		$electroneum->_print($available); ?>
	<h4>Unavailable</h4>
	<?php $unavailable = $electroneum->incoming_transfer('unavailable');
		$electroneum->_print($unavailable); ?>
	<h3>Get transfers</h3>
	<?php $get_transfers = $electroneum->get_transfers('pool', true);
		$electroneum->_print($get_transfers); ?>
	<h3>View key</h3>
	<?php $view_key = $electroneum->view_key();
		$electroneum->_print($view_key); ?>
<?php
	/*
	 *	Available Function
	 * --------------------------------------------------------------------
	 *	make_integrated_address => make a integrated address
	 *	$electroneum->make_integrated_address('');
	 * --------------------------------------------------------------------
	 *	split_integrated_address => Retrieve integrated address
	 *	$integrated_address = integrated address
	 *	$electroneum->splt_integrated_Address($integrated_address);
	 * --------------------------------------------------------------------
	 *	make_uri => useful for generating uri like monero:9aksi1o2...
	 *	$address = wallet address string
	 *	$amount (optional) = amount (library will convert into atomic unit, then use 1 xmr)
	 * 	$recipient_name (optional) = string name of the payment recipient
	 *	tx_description (optional) = string describing the reason for the tx
	 *	$electroneum->make_uri($address, $address, $amount, $recipient_name, $description);
	 * --------------------------------------------------------------------
	 *	parse_uri => parse the uri
	 * 	$uri = the uri
	 *	$electroneum->parse_uri($uri);
	 * --------------------------------------------------------------------
	 *	get_payments => Get a list of incoming payments using a given payment id (useful for verifying payment with plugins)
	 * 	$payment_id = id of payment
	 *	$electroneum->get_payments($payment_id);
	 * --------------------------------------------------------------------
	 *	transfer => transfer function
	 * 	$amount = amount
	 *	$address = wallet address (not your address)
	 *	$electroneum->transfer($amount, $address);
	 * --------------------------------------------------------------------
	 *	get_bulk_payments => Get a list of incoming payments using a given payment id or height
	 * 	$payment_id = array of payments id
	 *	$min_block_height = The block height at which to start looking for payments.
	 *	$electroneum->get_bulk_payments($payments_id, $min_block_height);
	 *
	*/
	?>
	@stop