@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav_exchange')
    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>


    <section id="access" class="ptb40 bg--secondary">
        <div class="container">
            <div class="alert alert-danger">
                <h3 class="text-center">ACCESS ONLY FOR TOKENHOLDERS</h3>
            </div>
            <div class="text-center">
                <p>
                <h4>
                    To access this page you need to hold minimum <b>100</b> NEXT tokens.
                </h4>

                </p>
            </div>
    </section>

@endsection
