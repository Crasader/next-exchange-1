@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>

    <section id="members" class="ptb40 bg--secondary">
        <div class="container">
            <h1 class="center">Hi {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}! Welcome
                to the members area.</h1><br>
            <p class="lead center">On this page you will find the balance of your NEXT token and further information about NEXT.exchange. At the moment we are planning some updates to the exchange (in beta phase). If you want to get involved, just click on the 'Exchange' link in the navigation menu. If you want more information about the ongoing developments, join the <a href="https://t.me/next_exchange">Telegram group</a>.</p><br><br><br>
            <br><br>

        <div class="row">
            <div class="col-sm-12 col-lg-4">
                <div class="c-landing-card u-mb-medium">

                    <div class="c-landing-card__img">
                        <img src="img/icon-intro1.svg" alt="iPhone icon">
                    </div>

                    <h2 class="c-graph-card__title">NEXT Token Balance</h2>
                    <p class="c-graph-card__date">Updated
                        on <?php echo (new \DateTime())->format('Y-m-d H:i:s'); ?></p>

                    @isset($wallet_balance)
                        <h4 class="c-graph-card__number"><b>{{ round($wallet_balance, 8) }}</b> NEXT</h4>
                    @endisset

                    <?php
                    $price_now = \App\Helpers\Helper::getCryptoPrice('', 'ETH') / 1000;
                    $price_ico = 0.26;

                    @$diff = 100 / (@$price_now / @$price_ico);

                    $bonus_balance = $referall->ico * '10';

                    $wallet_balance_refs = \App\Helpers\Helper::getWalletBalanceRef(\Auth::id());

                    ?>

                </div>
            </div>

            <div class="col-sm-12 col-lg-4">
                <div class="c-landing-card u-mb-medium">

                    <div class="c-landing-card__img">
                        <img src="img/icon-intro2.svg" alt="Graphs icon">
                    </div>


                    <h3 class="c-graph-card__title">Total Tokens Allocated</h3>
                    <p class="c-graph-card__date">Amount is based after token burn/nullify</p>
                    <!-- <p class="c-graph-card__date"> </p>
                    <h4 id="token-sale-progress-value" class="c-graph-card__number">loading</h4>
                    <p class="c-graph-card__status">&nbsp;</p> -->
                    <p class="c-graph-card__date"></p>
                    <h4 class="c-graph-card__number">27450992 M</h4>

                    <!-- <a class="c-btn c-btn--info" href="#">View your reports</a> -->
                </div>
            </div>

            <div class="col-sm-12 col-lg-4">
                <div class="c-landing-card u-mb-medium">

                    <div class="c-landing-card__img">
                        <img src="img/icon-intro3.svg" alt="Code icon">
                    </div>


                    <h3 class="c-graph-card__title">Referral Program</h3>
                    <p class="c-graph-card__date">At this moment the referral program is <b>on hold</b></p>
                    <h4 class="c-graph-card__number"><b>{{ $referall->count }}</b> referral's</p></h4>
                   <!-- <h4 class="c-graph-card__url"></h4>


                    @isset($bonus_balance)
                        <div id="wallet_tokens">Bonus tokens:
                            <b>{{ $bonus_balance + $wallet_balance_refs }}</b> NEXT
                        </div>
                    @endisset
                    <small>Bonus tokens are only allocated to referrals during the ICO. The bonus tokens will be applied to your main wallet after they are verified.
                    </small>
                    -->
                </div>
            </div>

        </div>

            <style>
                #authy h3 {
                    font-size: 20px;
                }
            </style>

            <br>

            <section id="authy">

                <div class="row">
                    <div class="col-sm-12 col-lg-4">
                        <div class="c-landing-card u-mb-medium">

                            <h3 class="c-graph-card__title">Two-factor Authentication</h3>
                            <div class="panel-body">
                                @if(!empty($success))
                                    <div class="alert alert-success">{{$success}}</div>
                                @endif
                                @if (count($errors) > 0)
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                <div class="col-xs-6"><br>
                                    @if($twofactor_enabled)
                                        <button type="button" class="btn btn-primary btn-block btn-flat"
                                                data-toggle="modal" data-target="#disable2FAModal">
                                            Disable
                                        </button>
                                        @include('auth.two-factor.disable-modal')
                                    @else
                                        <button type="button" class="btn btn-primary btn-block btn-flat"
                                                data-toggle="modal" data-target="#setup2FAModal">
                                            Setup now
                                        </button>
                                        @include('auth.two-factor.setup-modal')
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-4">
                        <div class="c-landing-card u-mb-medium">
                                <h3 class="c-graph-card__title">Last login from IP</h3>
                                    {{ $lastLoginIp }}
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-4">

                        <iframe src="https://tgwidget.com/channel/?id=5a76dc0583ba8846338b4567" frameborder="0" scrolling="no" horizontalscrolling="no" verticalscrolling="no" width="100%" height="460px" async></iframe>

                    </div>


                    <!--
                    <div class="col-md-8">
                        <div class="panel panel-default c-graph-card pb20">
                            <div class="c-graph-card__content">
                                <div class="panel-body">

                                                            <table class="table">
                                                                <thead>
                                                                <th class="f400">Assets</th>
                                                                <th class="f400">Available amount</th>
                                                                <th class="f400">Reserved amount</th>
                                                                <th class="f400">Total</th>
                                                                <th class="f400">Operation</th>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td class="">
                                                                        <div style="line-height: 22px; display: inline-block;"><img src="/img/coin/32/ETH.png" style="width: 22px; height: 22px; float: left; margin-right: 4px;"><span>Ethereum</span>
                                                                      <span class="color-gray">ETH</span></div>
                                                                    </td>
                                                                    <td class="">
                                                                        <span><span><span>0</span><span><br><span class="color-gray small"><span>≈</span><span>0.00</span><span>USD</span></span></span></span></span>
                                                                    </td>
                                                                    <td class="">
                                                                        <span><span><span>0</span><span><br><span class="color-gray small"><span>≈</span><span>0.00</span><span>USD</span></span></span></span></span>
                                                                    </td>
                                                                    <td class="">
                                                                        <span><span><span>0</span><span><br><span class="color-gray small"><span>≈</span><span>0.00</span><span>USD</span></span></span></span></span>
                                                                    </td>
                                                                    <td class="">
                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <button type="button" class="btn-icon"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                                                                            <button type="button" class="btn-icon"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                                                                            <button type="button" class="btn-icon"><i class="fa fa-exchange" aria-hidden="true"></i></button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="">
                                                                        <div style="line-height: 22px; display: inline-block;"><img src="/img/coin/32/NEXT.png" style="width: 22px; height: 22px; float: left; margin-right: 4px;"><span>Next</span>
                                                                            <span class="color-gray">NEXT</span></div>
                                                                    </td>
                                                                    <td class="">
                                                                        <span><span><span>0</span><span><br><span class="color-gray small"><span>≈</span><span>0.00</span><span>USD</span></span></span></span></span>
                                                                    </td>
                                                                    <td class="">
                                                                        <span><span><span>0</span><span><br><span class="color-gray small"><span>≈</span><span>0.00</span><span>USD</span></span></span></span></span>
                                                                    </td>
                                                                    <td class="">
                                                                        <span><span><span>0</span><span><br><span class="color-gray small"><span>≈</span><span>0.00</span><span>USD</span></span></span></span></span>
                                                                    </td>
                                                                    <td class="">
                                                                        <div>

                                                                            <style>

                                                                                .btn-icon { background-color: #fff; border: 1px solid #cccc; padding-left: 10px; padding-right: 10px; }
                                                                            </style>

                                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                                <button type="button" class="btn-icon"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                                                                                <button type="button" class="btn-icon"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                                                                                <button type="button" class="btn-icon"><i class="fa fa-exchange" aria-hidden="true"></i></button>
                                                                            </div>


                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            STOCKS -->
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

            </section>

        <!--
            <script>
                $(document).ready(function() {
                    var eth_addr = '0xe0A5858763F6cBeB6ab0A00d62B6e04F9d05aE56';
                    var api_call = 'https://api.etherscan.io/api?module=account&action=balance&address=' + eth_addr;
                    make_api_call();
                    var curr_refresh_seconds = <?php echo REFRESH_SECONDS ?>;
                    setInterval(function() {
                        if ( curr_refresh_seconds > 0 ) {
                            var seconds = 'seconds';
                            if ( curr_refresh_seconds === 1 ) { seconds = 'second' }
                            $('#refreshing-progress').html('refresh in ' + curr_refresh_seconds + ' ' + seconds);
                        }
                        else {
                            $('#refreshing-progress').html('refreshing ...');
                            make_api_call();
                            curr_refresh_seconds = <?php echo REFRESH_SECONDS ?>;
                        }
                        curr_refresh_seconds--;
                    }, 1000);
                    function make_api_call() {
                        $.get(api_call, function(data) {
                            if ( typeof data === 'object'
                                && typeof data.result === 'string'
                                && data.result.trim() !== ''
                            ) {
                                var eth = data.result.trim();
                                var total_supply = '120000000';
                                var allocated = '48000000';
                                var reserved = '960000';
                                var cost_token = '1000';
                                var rlvnt_eth = eth.slice(0, 5);
                                var rest_eth = eth.slice(5).slice(0, 5);
                                var curr_eth = Number(rlvnt_eth + '.' + rest_eth) / 1000;
                                var curr_percent = 100 / ((total_supply / cost_token) / ((total_supply - allocated - reserved - curr_eth)  / cost_token));

                                var avail_tokens = (total_supply - allocated - reserved - (curr_eth * cost_token)).toString().slice(0,8);

                                $('#token-sale-progress-value').html(
                                    //'<b>Pre-ICO:</b> ' + curr_eth.toString().slice(0,5) + ' / 60000 Ether (~' + curr_percent.toFixed(2) + '%)'
                                    avail_tokens + ' / 120M <span class="small">(~' + curr_percent.toFixed(2) + '%)<span>'
                                );
                            }
                        });
                    }
                });
            </script>


            <span class="c-divider has-text u-mb-medium">TOKENSALE</span>
            <section id="tokensale" class="text-center ptb20">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#ethereum" role="tab" data-toggle="tab">Ethereum (ETH)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#bitcoin" role="tab" data-toggle="tab">Bitcoin (BTC)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#bank" role="tab" data-toggle="tab">Bank transfer (IBAN)</a>
                </li>
            </ul>

            <div class="tab-content">
            <div role="tabpanel" class="tab-pane in active ptb40 card" id="ethereum">

            <p class="lead center">To buy NEXT Tokens, send ETHER (min. 0.1 ETH) to the address below:</p>
            <p class="center">Don't send ETHER from <span class="text-red">Cryptopedia</span>, <span class="text-red">Coinbase</span> or <span class="text-red">EtherDelta</span>. They will get your tokens! Need help? <a href="https://medium.com/nextexchange/how-to-participate-in-the-next-exchange-token-crowdsale-a-beginners-guide-7fd95e938b90" target="_blank">Read this tutorial</a></p>
                <p class="f16 red text-white"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Due to heavy traffic on the Ethereum blockchain, use a higher gasprice of <a id="popover" class="btn" data-toggle="popover" data-container="#popover-container" data-content="<img src='/img/60GWEI-gasprice.png'/>"  data-placement="bottom"><b>60 GWEI</b></a> Otherwise, your transaction will fail. <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></p>
                <div class="container" id="popover-container"></div>

                <script>

                    $(()=>$('[data-toggle="popover"]').popover({
                        html: true,
                        trigger: 'hover'
                    }));

                </script>

            <section id="icotokens" class="container">

                <div class="row align-center" id="tokensale">
                    <div class="col-md-2 pre-ico">
                        ICO (OPEN)
                    </div>
                    <div class="col-md-8 address">
                        <span class="status">ETH</span>
                        <span id="p2">0x1bf357bb34bdbce6dad4c0fc387db871cec6c8c2</span>
                        <a onclick="copyToClipboard('p2')"><i class="fa fa-clipboard"
                                                              aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-2 amount">
                        1 ETH = 1000 NEXT
                    </div>
                </div>
        </section>
                <br>
                <p>To see NEXT tokens in your MEW (MyEtherWallet), please follow <a href="https://medium.com/nextexchange/how-to-see-and-add-next-tokens-to-your-erc20-wallet-1d163bca04c1" target="_blank">these instructions</a>.</p>

            </div>
                <div role="tabpanel" class="tab-pane fade" id="bitcoin">
                    <form id="btcbuy" onsubmit="return false;">
                        <div class="row">
                            <div class="col-md-6 card ptb40">
                                <h4>How many NEXT tokens would you like to buy?</h4>
                                <br><br>
                        <div class="input-group">
                            <input type="text" name="next_amount" class="form-control" placeholder="Enter amount" aria-describedby="basic-addon2">
                            <span class="input-group-addon" id="basic-addon2">NEXT</span>
                        </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Generate</button>
                            </div>
                            <div class="col-md-6" id="btcgen">
                            </div>
                        </div>
                    </form>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="bank">
                    <div class="col-12 card ptb40">
                        <h4>Bank transfer (IBAN)</h4><br>
                        <ul>
                            <li>1. The minimum that we accept is 1000 NEXT (current price: $ <?= round($price_now * 1000, 2) ?> (€. <?= round($price_now / 1.19880 * 1000, 2) ?>))</li>
                            <li>2. If you want to buy more tokens, just multiply the amount (per 1000 NEXT).</li>
                            <li>3. Transfer the amount in <b>EURO</b> to the following bank account and put the <b>amount of NEXT</b> and <b>UserID <?= Auth::id(); ?></b> in the description:</li>
                        </ul><br>
                        <badge class="badge badge-info f16">NL21 BUNQ 2205 6228 38</badge><br>
                        <b>BIC/SWIFT:</b> BUNQNL2A<br>
                        <b>Bank identifier:</b> BUNQ<br><br>
                        <p>Account holder name: <br>NEXT Holdings N.V. (i.o.)<br>
                        Singel 250<br>
                            1016 AB Amsterdam<br>

                        </p>
                    </div>

                </div>


            </div>
            </section>
        <div class="container ptb20">
            <p class="small">You can check on this page the amount of tokens you received, or check <a href="https://etherscan.io/token/0x4e005a760e00e17c4912a8070eec047cfecbabbb">here</a>. The tokens will be tradable after the initial coin offering on the NEXT.exchange stock exchange and others, like HITBTC and Poloniex.
        Any questions? Just send us an email on <a href="mailto:info@next.exchange">info@next.exchange</a>.</p>
        </div>

        </div>
    </section>

    <script>
        $('#btcbuy').submit(function(e) {
            e.preventDefault();

            // get all the inputs into an array.
            var amount = $('#btcbuy :input[name=next_amount]').val();

            //$('#btcgen').html('Amount of NEXT to buy: '+inputs+' NEXT');
            // QRCODE + BTC AMOUNT calculator
            //alert(inputs);
            var data = 'amount=' +amount;


            //Use Ajax to submit the form data
            $.ajax({
                headers:{'X-CSRF-Token': '<?php echo csrf_token() ?>'},
                url: "/addr/bitcoin/",
                type: 'POST',
                data: data,
                success: function(result) {
                    // ... Process the result ...
                    //alert(formData.db + formData.Start + formData.End);
                    //alert(result);
                    $('#btcgen').html(result);

                }

            });

            //$('#example').empty();


        });
    </script>
    -->

@endsection
