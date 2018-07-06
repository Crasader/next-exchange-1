@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    <!-- <div class="banner banner-mini bg-blue">
        <span data-lang-id="about_pageTitle">Login</span>
    </div>
-->

    <style>
        .login {
            background-color: #fff !important;
        }

        .login p {
            padding-top: 30px;
            padding-bottom: 20px;
        }

        .login h3 {
            padding-bottom: 20px;
        }

        .login button {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>

    <section class="login">
        <div class="container">
            <div class="col-md-12 login-box register-box">
                <h3 style="margin-left: 16px;" class="text-center">Login</h3>
                <a class="flat-butt-sm btn-social btn-facebook" href="/social/redirect/facebook">
                    <i class="fa fa-facebook fa-fw"></i> Login with Facebook
                </a>


                <p style="margin-left: 16px; margin-bottom:20px" class="text-center">Or login with your NEXT account</p>

                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    @if ($errors->has('2fatoken'))
                        <div class="col-md-12 has-error">
                          <span class="help-block">
                                        <strong>{{ $errors->first('2fatoken') }}</strong>
                                    </span>
                        </div>
                    @endif

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <!-- <label for="email" class="col-md-4 control-label">E-Mail Address</label> -->

                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control next-input"
                                   placeholder="Enter e-mail address" name="email" value="{{ old('email') }}"
                                   required autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <!-- <label for="password" class="col-md-12 control-label">Password</label> -->

                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control next-input"
                                   placeholder="Enter your password" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    @if(config('settings.reCaptchStatus'))
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="g-recaptcha" data-sitekey="{{ env('RE_CAP_SITE') }}"></div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                <!--
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>
                    -->
                    <div class="form-group">
                        <div class="col-md-12 col-md-offset-4 text-center">
                            <button type="submit" class="btn-primary signup-btn" style="cursor: pointer">
                                Login
                            </button>


                            <a class="btn-link block forgot-link" href="{{ route('password.request') }}"
                               style="color: #000; font-size: 80%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.333 7.3A.667.667 0 1 1 4 7.3V4.5C4 2.206 5.783.333 8 .333s4 1.873 4 4.167v2.8a.667.667 0 1 1-1.333 0V4.5c0-1.572-1.202-2.833-2.667-2.833-1.465 0-2.667 1.261-2.667 2.833v2.8zm-2 .667a.667.667 0 0 0-.666.666v5.034c0 .368.298.666.666.666h9.334a.667.667 0 0 0 .666-.666V8.633a.667.667 0 0 0-.666-.666H3.333zm0-1.334h9.334a2 2 0 0 1 2 2v5.034a2 2 0 0 1-2 2H3.333a2 2 0 0 1-2-2V8.633a2 2 0 0 1 2-2zM8 13c-.6 0-1-.32-1-.8V9.8c0-.48.4-.8 1-.8s1 .32 1 .8v2.4c0 .48-.4.8-1 .8z"></path>
                                </svg>
                                Forgot Your Password?
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts_footer')
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>

@endsection