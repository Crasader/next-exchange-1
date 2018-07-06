@extends('_layouts.main')

@section('content')


    @include('_layouts.topnav_exchange')
    @include('_partials.flashalert')


    <div class="container ptb40">
        <div class="row">
            <div class="col-sm-12">
                @if (count($transactions) > 0)
                    <section class="transactions">
                        @include('transactions.load')
                    </section>
                @endif
            </div>
        </div>
    </div>


@endsection

@section('styles')

@endsection

@section('scripts_footer')
    <script type="text/javascript">

        $(function () {
            $('body').on('click', '.pagination a', function (e) {
                e.preventDefault();

                $('#load a').css('color', '#dfecf6');
                $('#load').append('<img style="position: absolute; left: 50%; top: 50%; z-index: 100000;" width="150" height="100" src="/img/loader.gif"/>');

                var url = $(this).attr('href');
                getTransactions(url);
                window.history.pushState("", "", url);
            });

            function getTransactions(url) {
                $.ajax({
                    url: url
                }).done(function (data) {
                    $('.transactions').html(data);
                }).fail(function () {
                    alert('Transactions could not be loaded.');
                });
            }
        });
    </script>
@endsection
