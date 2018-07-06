@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')
    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>

    <section class="section-header">
        <div class="container">

            <h1 class="text-white"> Here will come the market information which normally loads with vue!</h1>

        </div>
    </section>

@endsection