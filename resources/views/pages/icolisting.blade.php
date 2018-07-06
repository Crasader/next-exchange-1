@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')

    <div class="container">
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="/">Home</a>
        <a class="breadcrumb-item" href="/ico/listing">ICO Listing</a>
    </nav>
    </div>



    <section id="apply">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 offset-lg-2 col-md-12 offset-md-1 text-center mt-4 preamble">
                    <h3 class="title mt-0 mb-3">Apply for your ICO</h3>

                    <p class="lead mb-5"> <span>Businesses will be given the exclusive opportunity to launch their token within NEXT.exchange.</span><br><br></p>
                </div>


            </div>

            <div id="msg"></div>

            <style>
                @CHARSET "UTF-8";

                ul.trading__info {
                    position: relative;
                }

                .tradable__block {
                    display: inline-block;
                    width: 160px;
                    height: 160px;
                    background-color: rgba(0,0,0,0.05);
                    border: 1px solid rgba(0,0,0,0.1);
                }

                li.tradable__block {
                    margin-right: 10px;
                    margin-bottom: 10px;
                }

                .tradable__block.pinned {
                    position: absolute;
                    right: 0px;
                    top: 0px;
                }

                .tradable__block > div {
                    position: relative;
                    height: calc(100% - 20px);
                    margin: 10px;
                }

                .tradable__block > div > span.company {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    width: 100%;
                    height: auto;
                    line-height: 20px;
                    font-size: 18px;

                    color: #3980A8;
                }

                .tradable__block > div > span.ticker {
                    position: absolute;
                    top: 60px;
                    left: 0px;
                    font-size: 24px;
                    color: #555;
                }

                .tradable__block > div > span.ticker > span.up {
                    color: #60BB22;
                }

                .tradable__block > div > span.ticker > span.down {
                    color: #DD1316;
                }

                .tradable__block > div > span.volume {
                    position: absolute;
                    top: 90px;
                    left: 0px;
                    font-size: 16px;
                    color: #555;
                }

                .tradable__block > div > span.date__updated {
                    position: absolute;
                    top: 110px;
                    left: 0px;
                    font-size: 12px;
                    color: #ccc;
                }

                .tradable__block.unset span {
                    color: rgba(0,0,0,0.2) !important;
                }





                span.percent:before {
                    content: "%";
                }

                .price__change > span.up {
                    color: #080;
                }

                .price__change > span.down {
                    color: #800;
                }

                .price__change span.amount {

                }

                .price > span.currency {
                    color: #999;
                }

                .toolbar > .collection > li > h2 {
                    margin: 0px;
                }

                .collection {

                }

                .collection > li,
                .collection > span {
                    margin-right: 5px;
                    display: inline-block;
                }

                .collection > li:last-child,
                .collection > span:last-child {
                    margin-right: 0px;
                }

                .grid span > img.small.region {
                    position: absolute;
                    top: 1px;
                    right: 5px;
                    width: 18px;
                    height: 18px;
                }


                /*span.ticker {*/
                /*	padding: 4px 10px;*/
                /*	background-color: #FFA500;*/
                /*	border-radius:10px; -moz-border-radius:10px; -webkit-border-radius:10px;*/
                /*	margin-right: 10px;*/
                /*}*/

                #ico__listings {
                    position: relative;
                    display: block;
                    height: auto;
                    width: 100%;
                }

                #ico__listings > li {
                    position: relative;
                    display: inline-block;
                    width: 500px;
                    border: 1px solid transparent;
                    margin-bottom: 10px;
                    margin-right: 10px;
                    height: 300px;
                    background-color: #fff;
                }

                #ico__listings > li:hover {
                    border: 1px solid #ddd;
                }

                #ico__listings > li > span.display {
                    position: absolute;
                    text-align: right;

                    font-size: 20px;
                    bottom: 10px;
                    right: 10px;
                    background-color: #fff;
                }

                #ico__listings > li > div.overlay {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    display: none;
                    width: 150px;
                    height: 100%;
                    background-color: rgba(255,255,255,0.9);
                    border-right: 1px solid #ddd;
                }

                #ico__listings > li:hover > div.overlay {
                    display: block;
                }

                .can__stockquote {
                    position: relative;
                    background-color: transparent;
                    image-rendering: optimizeSpeed; // Older versions of FF
                image-rendering: -moz-crisp-edges; // FF 6.0+
                image-rendering: -webkit-optimize-contrast; // Webkit // (Safari now, Chrome soon)
                image-rendering: -o-crisp-edges; // OS X & Windows Opera (12.02+)
                image-rendering: optimize-contrast; // Possible future browsers.
                -ms-interpolation-mode: nearest-neighbor; // IE
                }

                .ico__info {
                    position: relative;
                }

                .ico__info > li {
                    position: relative;
                    line-height: 40px;
                }

                .ico__info > li.header {

                }

                .ico__info > li.header > h3 {
                    margin-bottom: 5px;
                    margin-top: 30px;
                }

                .ico__info > li.header:first-child > h3 {
                    margin-top: 0px;
                }

                .ico__info > li:not(.header) {
                    padding: 10px 10px;
                    margin-left: -10px;
                }

                .ico__info > li ~ li:not(.header) {
                    /*	border-top: 1px solid #eee;*/
                }

                .ico__info > li:last-child {
                    border-bottom: none;
                }

                .ico__info > li > ul > li {
                    position: relative;
                    display: block;
                    width: 100%;
                    height: auto;
                    line-height: 40px;
                    padding: 10px 10px 10px;
                    margin-left: -10px;
                    border-top: 1px solid #eee;
                }

                .ico__info > li > ul > li:after {
                    content: "."; display:block; clear:both; height:0; visibility:hidden;
                }

                .ico__info > li:after {
                    content: "."; display:block; clear:both; height:0; visibility:hidden;
                }

                .ico__info > li span.display {

                    font-size: 20px;
                    margin-right: 10px;
                    top: -8px;
                    z-index: 10;
                }

                .ico__info > li span.display > span.new {
                    position: relative;
                    top: -10px;
                    left: 5px;
                    background-color: #5E8938;
                    color: #fff;
                    padding: 2px 4px;

                    font-size: 10px;
                    line-height: 10px;
                    border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;
                }

                .ico__info > li span.volume {
                    position: absolute;
                    left: 14px;
                    top: 30px;
                    color: #888;

                    font-size: 10px;
                    margin-right: 10px;
                }

                .ico__info > li > img {
                    width: 16px;
                    height: 16px;
                    position: absolute;
                    left: -14px;
                    top: 12px;
                }

                span.current__price {

                    font-size: 20px;
                    margin-right: 10px;
                }

                span.current__price > span.currency {
                    color: #999;
                    width: 40px;
                    margin-left: 5px;
                    font-size: 0.6em;
                    top: -5px;
                }

                #ico__listings > li > span.current__price {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    font-size: 30px;
                    margin-right: 0px;
                }

                .ico__info > li span.container {
                    position: relative;
                    height: auto;
                }

                .ico__info > li span.button {
                    position: relative;
                    top: -2px;
                }

                .ico__info > li:hover {
                    position: relative;
                }



            </style>

<!--
            <div class="row center ico">
                <div class="col-lg-12">

                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">NEXT</span>
                            <span class="ticker"><span class="">▶︎ 0,26 USD</span></span>
                            <span class="volume">20,000,000</span>
                            <span class="date__updated"></span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated"></span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated"></span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated"></span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated"></span>
                        </div></li>



                </div>

            </div>
            -->


            <p class="center"><button id="init-form" type="button" class="btn btn-large btn-primary">Let's get started</button></p>


            <style>
                .control-label { font-size: 16px; color: #243037; }
                .help-block { font-size: 11px; }
                .top_wizard { background-color: #00b3ee; color: #fff; padding: 5px;  }
                .sw-theme-default > ul > li  { padding: 20px; }
                .sw-theme-default > ul.step-anchor > li > a { color: #fff !important; }
                .sw-theme-default > ul.step-anchor > li.active > a {
                    border: none !important;
                    background: transparent !important;
                    color: #243037 !important;

                }
                .sw-theme-default .step-content { padding-top: 20px; }
                input { padding: 0px 10px 0px 0px !important; margin:0 !important }


            </style>


        <form action="#" id="myForm" role="form" data-toggle="validator" method="post" accept-charset="utf-8">
            <section id="smartwizard" class='container' style="display: none;">

                <ul class="top_wizard">
                    <li><a href="#step-1">ICO<br /><small>ICO details</small></a></li>
                    <li><a href="#step-2">Additional<br /><small>More information</small></a></li>
                    <li><a href="#step-3">Summary<br /><small>Overview</small></a></li>
                </ul>


                <div >
                    <div id="step-1">
						<div id="form-step-0" role="form" data-toggle="validator" novalidate="true">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="name">ICO name</label>
                            <div class="col-md-12">
                                <input id="name" name="name" type="text" required placeholder="" class="form-control input-md">
                                <span class="help-block">Write the name of the ICO</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="symbol">ICO symbol</label>
                            <div class="col-md-12">
                                <input id="symbol" name="symbol" required type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">Symbol of your token, like Ethereum is ETH</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="total_supply_token">What is the total supply of the token?</label>
                            <div class="col-md-12">
                                <input id="total_supply_token" required name="total_supply_token" type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">The total supply of the token</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="stage">Stage of ICO</label>
                            <div class="col-md-4">
                                <select id="stage" name="stage" class="form-control">
                                    <option value="In development">In development</option>
                                    <option value="PRE ICO">PRE ICO</option>
                                    <option value="ICO">ICO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="launch_date">Date of launch</label>
                            <div class="col-md-12">
                                <input id="launch_date" required name="launch_date" type="date" placeholder="MM/DD/YYYY" class="form-control input-md">
                                <span class="help-block">For ex. 12/01/2017</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="initial_price">Initial price</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input id="initial_price" required name="initial_price" class="form-control" placeholder="" type="text">
                                </div>
                                <span class="help-block">The initial price in $ for one token</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="short_description">Short description</label>
                            <div class="col-md-12">
                                <input id="short_description" required  name="short_description" type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">Describe your token in one line</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="full_description">Full description</label>
                            <div class="col-md-12">
                                <textarea id='full_description' required name="full_description"></textarea>
                            <span class="help-block">Pitch here your token and why investors needs your token, what are the bonuses</span>
                            </div>
                        </div>
</div>

                    </div>
                    <div id="step-2" class="">
						<div id="form-step-1" role="form" data-toggle="validator" novalidate="true">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="website_url">Website URL</label>
                            <div class="col-md-12">
                                <input id="website_url" required name="website_url" type="text" placeholder="https://next.exchange" class="form-control input-md">
                                <span class="help-block">Please include full url with http(s)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="whitepaper_url">Whitepaper URL</label>
                            <div class="col-md-12">
                                <input id="whitepaper_url" required  name="whitepaper_url" type="text" placeholder="https://next.exchange/whitepaper" class="form-control input-md">
                                <span class="help-block">Where we can find more information</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="twitter_url">Twitter URL</label>
                            <div class="col-md-12">
                                <input id="twitter_url" required  name="twitter_url" type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">Where we can find more information</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="facebook_url">Facebook URL</label>
                            <div class="col-md-12">
                                <input id="facebook_url" required name="facebook_url" type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">Where we can find more information</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="telegram_url">Telegram URL</label>
                            <div class="col-md-12">
                                <input id="telegram_url" required  name="telegram_url" type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">Where we can find more information</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="bitcointalk_url">BitCoinTalk URL</label>
                            <div class="col-md-12">
                                <input id="bitcointalk_url" required name="bitcointalk_url" type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">Where we can find more information</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="official_video_url">Official video URL</label>
                            <div class="col-md-12">
                                <input id="official_video_url"  required name="official_video_url" type="text" placeholder="" class="form-control input-md">
                                <span class="help-block">Where we can find more information</span>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div id="step-3" class="">
						<div id="form-step-2" role="form" data-toggle="validator" novalidate="true">
                        <h3>Summery</h3>
                        <div class="summary"></div>
                        </div>
                    </div>

                </div>

            </section>

</form>
            <script>
				var steps_number =  0;
                $('#init-form').one("click", function(e) {
                    e.stopPropagation();
                    $(this).remove();
                    $('.ico').remove();
                    var form = $("#smartwizard").show();
                });

                $(document).ready(function(){
					$('.summary').html('');
					var btnFinish = $('<button></button>').text('Finish')
                                             .addClass('btn btn-info')
                                             .on('click', function(){ 
                                                    if( !$(this).hasClass('disabled')){ 
                                                        var elmForm = $("#myForm");
                                                        if(elmForm){
                                                            elmForm.validator('validate'); 
                                                            var elmErr = elmForm.find('.has-error');
                                                            if(elmErr && elmErr.length > 0){
                                                                //alert('Oops we still have error in the form');
                                                                var data='Please fill all required information';
                                                                $('#msg').html(data);
                                                                return false;    
                                                            }
                                                            else{
																name = $('#name').val();
																symbol = $('#symbol').val();
																total_supply_token = $('#total_supply_token').val();
																stage = $('#stage').val();
																launch_date = $('#launch_date').val();
																initial_price = $('#initial_price').val();
																short_description = $('#short_description').val();
																full_description = $('#full_description').val();
																website_url = $('#website_url').val();
																whitepaper_url = $('#whitepaper_url').val();
																twitter_url = $('#twitter_url').val();
																facebook_url = $('#facebook_url').val();
																telegram_url = $('#telegram_url').val();
																bitcointalk_url = $('#bitcointalk_url').val();
																official_video_url = $('#official_video_url').val();
																$.ajax({
																	headers:{'X-CSRF-Token': '<?php echo csrf_token() ?>'},
																	url: "<?php echo url('/ico/create') ?>",
																	type: 'Post',
																	data: {'name':name, 'symbol':symbol, 'total_supply_token':total_supply_token, 'stage':stage,  'launch_date':launch_date, 'initial_price':initial_price, 'short_description':short_description, 'full_description':full_description, 'website_url':website_url, 'whitepaper_url':whitepaper_url, 'twitter_url':twitter_url,
																	'facebook_url':facebook_url, 'telegram_url':telegram_url, 'bitcointalk_url':bitcointalk_url, 'official_video_url':official_video_url},
																	dataType: "json",

																	success: function(data){
																		if(data.success){
																			$('#msg').show();
																			$('#msg').html(data.message);
																			$('#smartwizard').remove();
																		} else {
																			$('#msg').show();
																			$('#msg').html('Profile Update NOT updated, please try again');
																		}
																	},
																	error: function(data){
																		$('#msg').show();
																		$('#msg').html(data.message);
																	},
																});
                                                               
																return false;
                                                            }
                                                        }
                                                    }
                                                });
					var btnCancel = $('<button></button>').text('Cancel')
                                             .addClass('btn btn-danger')
                                             .on('click', function(){ 
                                                    $('#smartwizard').smartWizard("reset"); 
                                                    $('#myForm').find("input, textarea").val(""); 
                    });                                        
                    $('#smartwizard').smartWizard({
                        selected: 0,  // Initial selected step, 0 = first step
                        keyNavigation:true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                        autoAdjustHeight:false, // Automatically adjust content height
                        cycleSteps: false, // Allows to cycle the navigation of steps
                        backButtonSupport: true, // Enable the back button support
                        useURLhash: true, // Enable selection of the step based on url hash
                        lang: {  // Language variables
                            next: 'Next',
                            previous: 'Previous'
                        },
                        toolbarSettings: {
                            toolbarPosition: 'bottom', // none, top, bottom, both
                            toolbarButtonPosition: 'right', // left, right
                            showNextButton: true, // show/hide a Next button
                            showPreviousButton: true, // show/hide a Previous button
                            toolbarExtraButtons: [btnFinish, btnCancel]
                        },
                        anchorSettings: {
                            anchorClickable: true, // Enable/Disable anchor navigation
                            enableAllAnchors: false, // Activates all anchors clickable all times
                            markDoneStep: true, // add done css
                            enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                        },
                        contentURL: null, // content url, Enables Ajax content loading. can set as data data-content-url on anchor
                        disabledSteps: [],    // Array Steps disabled
                        errorSteps: [],    // Highlight step with errors

                        transitionEffect: 'fade', // Effect on navigation, none/slide/fade
                        transitionSpeed: '400',
                });   
               $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
                var elmForm = $("#form-step-" + stepNumber);
                // stepDirection === 'forward' :- this condition allows to do the form validation 
                // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
                if(stepDirection === 'forward' && elmForm){
                    elmForm.validator('validate'); 
                    var elmErr = elmForm.children('.has-error');
                    if(elmErr && elmErr.length > 0){
                        // Form validation failed
                        return false;    
                    }
                }
                return true;
            });
            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
				
                // Enable finish button only on last step
                if(stepNumber == 2){
					console.log("third step start");
					var name = $('#name').val();
					var symbol = $('#symbol').val();
					var total_supply_token = $('#total_supply_token').val();
					var stage = $('#stage').val();
					var launch_date = $('#launch_date').val();
					var initial_price = $('#initial_price').val();
					var short_description = $('#short_description').val();
					var full_description = $('#full_description').val();
					var website_url = $('#website_url').val();
					var whitepaper_url = $('#whitepaper_url').val();
					var twitter_url = $('#twitter_url').val();
					var facebook_url = $('#facebook_url').val();
					var telegram_url = $('#telegram_url').val();
					var bitcointalk_url = $('#bitcointalk_url').val();
					var official_video_url = $('#official_video_url').val();
						var summary='<table class="table table-striped">'+
									'<tr>'+
										'<td>Coin Name</td>'+
										'<td>'+name+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Symbol</td>'+
										'<td>'+symbol+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Total Supply Token</td>'+
										'<td>'+total_supply_token+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>launch Date</td>'+
										'<td>'+launch_date+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Initial Price</td>'+
										'<td>'+initial_price+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Short Description</td>'+
										'<td>'+short_description+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Full Description</td>'+
										'<td>'+full_description+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Website Url</td>'+
										'<td>'+website_url+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Whitepaper Url</td>'+
										'<td>'+whitepaper_url+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Twitter Url</td>'+
										'<td>'+twitter_url+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>facebook_url</td>'+
										'<td>'+facebook_url+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Telegram Url</td>'+
										'<td>'+telegram_url+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Bitcointalk Url</td>'+
										'<td>'+bitcointalk_url+'</td>'+
									'</tr>'+
									'</table>'+
								'</div>';	
													
					$('body').find('.summary').append(summary);
					 
                }
                else if(stepNumber == 3)
                {
					
                    $('.btn-finish').removeClass('disabled'); 
					
				}
                
                else{
					
					
                    $('.btn-finish').addClass('disabled');
                }
            });        
        });
</script>






            <br><br>

            <style>
                ul.conditions { line-height: 14px; margin-top: 20px; }
            </style>

            <div class="row ptb40">
                <div class="col-lg-12">
                    <small>


                        <h3>Conditions</h3>

                        <ul class="conditions">



                            <li><span>For elligability your business must:</span></li>

                            <li><span class="indent">* be incorporated as a legal entity</span></li>

                            <li><span class="indent">* have not launched / will not launch a similar or identical sale (directly or indirectly)</span></li>

                            <li><span class="indent">* not have received (through investment or revenue) a sum of more than 1,000,000 (one million euros) since the date of incorporation</span></li>

                            <li><span class="indent">* have a White-Paper detailing the use of the token/coin and have a clear definition of the services provided</span></li>

                            <li><span class="indent">* your business intends to launch its initial coin offering right now</span></li>

                            <li><span>All applications will undergo a detailed check regarding the intentions, whitepaper and proposed funding methods. Our team will also undertake background checks to ensure compliance with regulatory processes and requirements. In addition all applicants will be required to assist in such processes as KYC (Know Your Customer) and AML (Anti-Money Laundering) throughout the ICO lifetime.</span></li>

                        </ul>


                    </small>
                </div>
            </div>
        </div>
    </section>
    <style>

table.table.table-striped{
	width: 100%;
    display: table;
    border-collapse: collapse;
    border-spacing: 0;
}
table.table-striped tbody{
	display: table-row-group;
    vertical-align: middle;
    border-color: inherit;
}
table.table-striped tr{
	    border-bottom: 1px solid #d0d0d0;
}
table.table-striped th td{
	padding: 15px 5px !important;
    display: table-cell important;
    text-align: left important;
    vertical-align: middle important;
    border-radius: 2px important;
}
    </style>
@stop
