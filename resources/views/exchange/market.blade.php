@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav_exchange')

    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>

    <style>
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place front is invalid - may break your css so removed */
            padding-top: 100px; /* Location of the box - don't know what this does?  If it is to move your modal down by 100px, then just change top below to 100px and remove this*/
            left: 0;
            right: 0; /* Full width (left and right 0) */
            top: 0;
            bottom: 0; /* Full height top and bottom 0 */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            z-index: 9999; /* Sit on top - higher than any other z-index in your site*/
        }

        .modal-backdrop {
            /* bug fix - no overlay */
            display: none;
        }
    </style>

    <div id="app">
        <section id="markets" class="ptb40 bg--secondary">
            <div class="container">
                <next-market></next-market>
            </div>
        </section>
    </div>

@endsection

@section('scripts_footer')
    <script src="{{ asset('/js/app.js') }}"></script>
@endsection