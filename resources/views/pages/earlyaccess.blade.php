@extends('_layouts.main')


@section('content')

    @include('_layouts.topnav')



    <div class="container">
        <nav class="breadcrumb">
            <a class="breadcrumb-item" href="/">Home</a>
            <a class="breadcrumb-item" href="/tokenholder/access">Early access</a>
        </nav>
    </div>


    @include('_partials.status-panel')


    <section id="apply">
        <div class="container">
            <div class="row">
                <center class="col-lg-12 offset-lg-2 col-md-12 offset-md-1 text-center mt-4 preamble">
                     <h3 class="title mt-0 mb-3">Apply for early access to the exchange</h3>

                    <p class="lead mb-5"> <span>During December 14th and December 31th only tokenholders, with at least 100 NEXT tokens, can enter the NEXT.exchange. To get early access we need to verify your account and amount of tokens. <br>Please fill out the form so we can verify and send you the invite link of the exchange.
                           </span><br><br></p>

            </div>
        </div>



        <section class="switchable ">
            <div class="container">
                <div class="row">

                    <style>
                        .form-modern { padding: 40px; }
                        .form-modern .btn { padding-left: 10px !important; }
                    </style>
                    <div class="col-12">
                        <div class="row card ">



                            @if (session('message'))
                                <div class="alert alert-info">{{ session('message') }}</div>
                            @endif

                            <ul>
                                @if(isset($message))
                                    <li>{{ $message }}</li>
                                @endif
                            </ul>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            {!! Form::open(array('url' => '/tokenholder/access', 'class' => 'form form-modern ')) !!}

                            <label for="accountId">Please select your country of residence</label>
                            <div class="bfh-selectbox bfh-countries" data-country="US" data-flags="true" data-name="country">
                                <input type="hidden" value="" name="country">
                                <a class="bfh-selectbox-toggle" role="button" data-toggle="bfh-selectbox" href="#">
                                    <span class="bfh-selectbox-option input-medium" data-option=""></span>
                                    <b class="caret"></b>
                                </a>
                                <div class="bfh-selectbox-options">
                                    <input type="text" class="bfh-selectbox-filter">
                                    <div role="listbox">
                                        <ul role="option">
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            <label for="accountId">Enter your ETH address</label>
                            <input type="text" id="etherId" name="etherId" class="form-control" aria-describedby="etherId">


                            <br>

                            <div class="form-group">
                                <label for="coin_trade">Which coin would you like to trade?</label>
                                <select class="form-control" id="coin_type" name="coin_type">
                                    <option value="ETN">Electroneum (ETN)</option>
                                    <option value="XVG">Verge (XVG)</option>
                                    <option value="LEND">ETHLend (LEND)</option>
                                    <option value="HTML">HTMLcoin (HTML)</option>
                                    <option value="SKY">Skycoin (SKY)</option>
                                    <option value="VISIO">Visio (VISIO)</option>
                                    <option value="VOISE">Voise (VOISE)</option>
                                    <option value="RUP">Rupee (RUP)</option>
                                </select>
                            </div>

                            <label for="accountId">Do you want to buy or sell this coin?</label>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="coin_buy" id="inlineCheckbox1" value="1"> Buy
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="coin_sell" id="inlineCheckbox2" value="1"> Sell
                                </label>
                            </div>


                            <br>

                            <label for="accountId">How many coins would you like to trade?</label>
                            <input type="text" id="coin_amount" name="coin_amount" class="form-control" aria-describedby="etherId">
                            <span id="etherId" class="form-text text-muted">Enter the amount of coins you would like to place on NEXT.exchange</span>

                            <br>




                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" id="fiat" name="fiat" value="1" style="margin-left: -20px !important"> Do you want to pair with fiat (USD/EUR)?
                                </label>
                            </div>

                            <br><br>


                            <div class="form-group">
                                {!! Form::submit('Send Enquiry',
                                  array('class'=>'btn btn-primary')) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!--end of row-->
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>




        </div>
    </section>

    <script src="/components/bootstrap-formhelpers/dist/js/bootstrap-formhelpers.min.js"></script>

@stop
