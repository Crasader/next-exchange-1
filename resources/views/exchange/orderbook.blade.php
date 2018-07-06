@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav_exchange')

    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>



    <div id="app">
        <section id="orderbook" class="ptb40 bg--secondary">
            <div class="container">
                <next-orderbook></next-orderbook>
            </div>
        </section>
    </div>

@endsection

@section('scripts_footer')
    <script src="{{ asset('/js/app.js') }}"></script>
@endsection