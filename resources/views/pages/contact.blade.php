@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')

    <style>
       [placeholder]:focus::placeholder {
           opacity: 1 !important;
       }
    </style>

    <section class="section-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-7"><h1 class="section-header__title">Contact us</h1>
                    <p class="section-header__desc"><span>Leave us a message, give us a call, or stop by our office anytime.<br/> We try to answer all enquiries and questions within 3 working days. <br><br>For urgent questions, please use the 'LEAVE A MESSAGE' box, which you can find on the bottom right of the screen.
                           </span></p></div>
                <div class="col-12 col-sm-5 text-right text-white"><b>Main office - AMSTERDAM</b>
                    <br>Singel 250<br> 1016 AB Amsterdam <br>the Netherlands
                    <br/>+31 85 8886240&nbsp;<i class="fa fa-phone-square" aria-hidden="true"></i>
                    <br><a href="mailto:info@next.exchange">info@next.exchange</a>&nbsp;<i class="fa fa-envelope"
                                                                                           aria-hidden="true"></i>
                    <br/>live:info_874540&nbsp;<i class="fa fa-skype" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </section>

    <section id="apply" style="background-color: #00AEFF;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 ptb40">
                    <div class="row card">
                        @if (session('message'))
                            <div class="alert alert-info">{{ session('message') }}</div>
                        @endif
                        <ul>
                            @if(isset($message))
                                <li>{{ $message }}</li>
                            @endif
                        </ul>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        {!! Form::open(array('url' => '/contact', 'class' => 'form', 'style'=>'padding:40px !important')) !!}
                        <div class="form-group">
                            {!! Form::label('Your Name') !!}
                            {!! Form::text('name', null,
                                array('required',
                                      'class'=>'form-control next-input',
                                      'placeholder'=>'Your name')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('E-mail Address') !!}
                            {!! Form::text('email', null,
                                array('required',
                                      'class'=>'form-control next-input',
                                      'placeholder'=>'Your e-mail address')) !!}

                            @if ($errors->has('email'))
                                <span class="help-block">
                                            <strong style="color:red;">{{ $errors->first('email') }}</strong>
                                        </span>
                            @endif

                        </div>
                        <div class="form-group">
                            {!! Form::label('Message') !!}
                            {!! Form::textarea('message', null,
                                array('required',
                                      'class'=>'form-control',
                                      'style'=>'height:150px',
                                      'placeholder'=>'Your message')) !!}
                        </div>
                        @if(config('settings.reCaptchStatus'))
                            <div class="form-group row">
                                <div class="col-sm-6 col-sm-offset-4">
                                    <div class="g-recaptcha" data-sitekey="{{ env('RE_CAP_SITE') }}"
                                         style="margin-left: -15px;"></div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong style="color:red;">{{ $errors->first('captcha') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <br>
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Send Enquiry',
                              array('class'=>'p-2 px-4 btn-primary')) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <!--end of row-->
                </div>
            </div>
            <!--end of row-->
        </div>
        <!--end of container-->
    </section>
@section('scripts_footer')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@stop