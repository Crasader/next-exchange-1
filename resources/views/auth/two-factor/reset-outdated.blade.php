@extends('_layouts.main')

@section('template_title')
    Two Factor Authentication Reset
@endsection

@section('content')
    <div class="container ptb40">
        <div class="row login-box">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <h3 class="panel-heading">2FA reset failed</h3>
                    <div class="panel-body">
                        <p>2FA reset token became outdated. Please try to request new reset upon: <br> <a
                                    href="{{ url('login') }}" class="btn btn-primary">login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection