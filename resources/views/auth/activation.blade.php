@extends('_layouts.main')

@section('template_title')
    {{ Lang::get('titles.activation') }}
@endsection

@section('content')

    @include('_layouts.topnav')


    <div class="container ptb40">
        <div class="row login-box">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <h3 class="panel-heading">{{ Lang::get('titles.activation') }}</h3>
                    <div class="panel-body">
                        <p>{{ Lang::get('auth.regThanks') }}</p>
                        <p>{{ Lang::get('auth.anEmailWasSent',['email' => $email, 'date' => $date ] ) }}</p>
                        <p>{{ Lang::get('auth.clickInEmail') }}</p>
                        <p><a href='/activation' class="btn btn-primary">{{ Lang::get('auth.clickHereResend') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection