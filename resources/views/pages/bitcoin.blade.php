@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')

    <div class="container">
    <h1>Bitcoin payment</h1>

        <div class="col-md-12 col-sm-12 col-xs-12" style="min-height: 260px">

            <div class="col-md-3">
                <div id="qrcode"><a href="bitcoin:{{ $address }}?amount=1"></a></div>

                <script>
                    $(function() {
                        $('#qrcode a').qrcode({
                            render: 'div',
                            text: "bitcoin:{{ $address }}?amount=1",
                            ecLevel: 'L',
                            size: "203"
                        });
                    });
                </script>
            </div>
            <div class="col-md-9">
                <br><br>

                Send manual <b>1 BTC</b> to:<br><br> <span class="label f14 label-success">{{ $address }}</span>
                <br>
                <br> After completing the payment, wait for 3 confirmations</a>. <br>As soon your payment is confirmed, we will add it to your deposit.
            </div>

        </div>


    </div>

    <script src="{{ asset('components/jquery-qrcode/dist/jquery-qrcode.min.js') }}"></script>

@stop