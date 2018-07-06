@extends('_layouts.main')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
@endsection

@section('content')

    @include('_layouts.topnav')


    <section class="currencies-body">
        <div class="container">
            <div class="col offset-md-3 login-box">
                <h3 style="margin-left: 16px;" class="text-center">Two-factor Authentication</h3>
                <p style="margin-left: 16px; margin-bottom:20px" class="text-center">Validate your two-factor authentication token</p>

                <form class="form-horizontal" method="POST" action="{{ url('auth/token') }}">
                    {{ csrf_field() }}

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="token" class="col-md-4 control-label">Enter Token</label>

                        <div class="col-md-12">
                            <input type="text" name="token" class="form-control" placeholder="Token" id="token" autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary signup-btn">
                                Verify Token
                            </button>

                            <div style="float: right">
                                <a href="#" class="reset2fa" id="reset2fa">Click here to reset 2FA</a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts_footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="{{ asset('/js/2fa.js') }}"></script>
    <script>
        $(function () {

            User.manage('{!! route('send-2fa-reset-email') !!}');
        });
    </script>
@endsection


