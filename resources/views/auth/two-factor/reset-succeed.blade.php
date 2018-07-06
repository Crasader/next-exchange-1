@extends('_layouts.main')

@section('template_title')
    Two Factor Authentication Reset
@endsection

@section('content')
    <div class="container ptb40">
        <div class="row login-box">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <h3 class="panel-heading">2FA reset successfully</h3>
                    <div class="panel-body">
                        <p>2FA has been reset for your account. Please login from here:<br> <a href="{{ url('login') }}"
                                                                                               class="btn btn-primary">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection