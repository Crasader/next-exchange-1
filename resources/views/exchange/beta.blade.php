@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')
    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>

    <section id="members" class="ptb40 bg--secondary">
        <div class="container">
            <h1 class="center">NEXT.EXCHANGE PLATFORM (BETA)<br> <label class="badge badge-sm badge-success">Operational</label></h1><br>

            <h4>Currently, we are preparing the exchange for tokenholders. In order to become a major exchange, it is necessary for everything to be perfect before the masses can enter our brand new platform. Below, we are providing a few of our platforms unique features as well as the launch dates for several coins and tokens.</h4>

            <div class="row ptb40">
                <div class="col-4"><img src="/img/beta/eth_wallet.png" class="img-fluid"></div>
                <div class="col-8">
                    <h3>Everyone should have a <b>secure cryptocurrency wallet</b></h3>
                <p>The NEXT.exchange platform is designed to have an intuitive, and easy-to-use interface. Secure cryptowallets will make sure that all of your funds are secure and easily accessible. From your own wallet, you will be able to instantly trade cryptocurrencies paired with other cryptos and fiats.</p></div>
            </div>

            <div class="row ptb40">
                <div class="col-8"><h3>Instant orders on a truly modern day exchange</h3>
                    <p>Our trading platform is connected directly to our own Blockchain instead of other exchanges. This allows you, our users, to carry out instant trades. Designed to be easily understandable for beginners too. Merely select a pair, input the desired amount, and exchange!</p></div>
                <div class="col-4"><img src="/img/beta/exchange_basic.png" class="img-fluid"></div>
            </div>

            <div class="row ptb40">
                <div class="col-4"><img src="/img/beta/eth_market.png" class="img-fluid"></div>
                <div class="col-8"><h3>Eye-catching market viewer</h3>
                    <p>Using our market viewer, you will be able to quickly see price overviews, volumes, as well as buy and sell orders for every tradable coin.</p></div>
            </div>

            <div class="row ptb40">
                <div class="col-8"><h3>Only the best coins up for trading</h3>
                    <p>Enjoy a wide selection of popular cryptocurrencies with fiat pairings. We only list digital assets that are backed by their communities, and as we grow together, so will the variety of coins that our exchange harbors.</p></div>
                <div class="col-4"><img src="/img/beta/coins_coins.png" class="img-fluid"></div>
            </div>

            <center>
            <p>In 'beta' phase only 'verified' tokenholders have full access to all functionalities on the exchange.</p>
<br>
                    <a href="{{ url('markets') }}" class="btn btn-primary">Enter the exchange</a>

                </center>

            <br>

        </div>
    </section>

@endsection