



@extends('_layouts.main')

@section('content')
    <div id="main-container container-fluid">
  <div class="banner banner-mini bg-blue">
        <span data-lang-id="dashboard_pageTitle">Tokens</span>
    </div>


                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                    @endif

                           @include('_partials.dashboard-sidenav')


                            <div class="page-wrapper" style="padding-left:240px; padding-top: 40px;">

                                @if ($message = Session::get('msg'))

                                    <div class="alert alert-success alert-dismissable" id="msg" style="display:none">

                                        <a href="#"  class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                        <strong>{{ $message }}</strong>

                                    </div>

                            @endif

                                <!-- ============================================================== -->
                                <!-- Container fluid  -->
                                <!-- ============================================================== -->
                                <div class="container-fluid">
                                    <!-- ============================================================== -->
                                    <!-- Bread crumb and right sidebar toggle -->
                                    <!-- ============================================================== -->
                                    <div class="row page-titles">
                                        <div class="col-md-5 align-self-center">
                                            <h3 class="text-themecolor">NEXT Token</h3>
                                        </div>
                                        <div class="col-md-7 align-self-center">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                                <li class="breadcrumb-item">Dashboard</li>
                                                <li class="breadcrumb-item active">Profile</li>
                                            </ol>
                                        </div>

                                    </div>
                                    <!-- ============================================================== -->
                                    <!-- End Bread crumb and right sidebar toggle -->
                                    <!-- ============================================================== -->

                                    <!-- ============================================================== -->
                                    <!-- Start Page Content -->
                                    <!-- ============================================================== -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h2 class="card-title">HOW TO BUY NEXT TOKEN</h2>
                                                    <p class="lead">Here you can buy your NEXT tokens. Please select the amount of tokens you want to buy and click on the payment method. After confirmation your NEXT tokens will be in your account.</p>
                                                    <form action="">
                                                        <fieldset class="form-group">
                                                            <label for="amount">Amount of NEXT <span class="">(+ 20% PRE-ICO BONUS)</span></label>
                                                            <div class="input-group">
                                                                <input type="text" name="amount" class="form-control" placeholder="Amount" aria-describedby="basic-addon2">
                                                                <span class="input-group-addon" id="basic-addon2">NEXT</span>
                                                            </div>
                                                            <p>With bonus you get: 1200 NEXT</p>
                                                        </fieldset>

                                                        <p>Pay with Ether (button): Send 20 ETH to smart contract address <span class="badge badge-info badge-md">0x983939823948204723</span></p>
                                                        <p>Pay with Bitcoin (button): Send 0.009409 BTC to address <span class="badge badge-info badge-md">3x3u03928e320709372</span></p>
                                                        <p>Pay with Litecoin (button): Send 20 LTC to address <span class="badge badge-info badge-md">0x983939823948204723</span></p>
                                                        <p>Pay with Dash (button): Send 20 DASH to address <span class="badge badge-info badge-md">0x983939823948204723</span></p>


                                                    </form>

                                                </div>
                                            </div>
                                            </div>
                                    </div>

                                </div>
                            </div>



        </div>

            <script type="text/javascript">
                /*	$(function () {
                 $('#myTab a:last').tab('show')
                 });*/
                $(document).ready(function() {

                    $('#update').click(function(){

                        var name= $('#name').val();
                        var occupation= $('#occupation').val();
                        var company= $('#company').val();
                        var address= $('#address').val();
                        var phone= $('#phone').val();
                        var city= $('#city').val();
                        var state= $('#state').val();
                        var postcode= $('#postcode').val();
                        var facebook= $('#facebook').val();
                        var twitter= $('#twitter').val();
                        var instagram= $('#instagram').val();
                        var linkedin= $('#linkedin').val();
                        var token= $('#token').val();


                        // alert(facebook);



                        $.ajax({
                            url: '/update',
                            type: 'POST',
                            data: {'_token':token ,'name':name,'occupation':occupation,'company':company,'address':address,'phone':phone,'city':city,'state':state,'postcode':postcode,'facebook':facebook,'twitter':twitter,'instagram':instagram,'linkedin':linkedin},
                            /* headers: {
                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                             }*/
                            success: function(data){
                                if(data=='ok'){
                                    $('#msg').show();

                                    $('#msg').html('Profile Update Successfully');
                                }
                            },
                            error: function(){},
                        });""
                    });
                });
                setTimeout(function() {
                    $('#msg').fadeOut('fast');
                }, 20000);




            </script>

@endsection
