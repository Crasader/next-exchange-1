<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name=viewport content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>NEXT.exchange - Decentralized trading platform for cryptocurrencies and ICO tokensale</title>
    <meta name="description" content="Decentralized stock exchange platform for trading fiat, cryptocurrencies, ICO, tokens, funds and digital assets. Join our tokensale!">

    <meta name="author" content="Christiaan van Steenbergen">
    <link rel="shortcut icon" href="/favicon/apple-icon.png">
    <meta name="referrer" content="no-referrer">

    <meta property="og:url" content="https://next.exchange/">
    <meta property="og:title" content="NEXT.exchange - Decentralized trading platform for cryptocurrencies and ICO tokensale">
    <meta property="og:description" content="Decentralized stock exchange platform for trading fiat, cryptocurrencies, ICO, tokens, funds and digital assets. Join our tokensale!">
    <meta property="og:image" content="https://next.exchange/uploads/social-og.png">
    <meta property="og:site_name" content="NEXT.exchange">
    <meta property="og:image:type" content="image/png">

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@nextexchange"/>
    <meta name="twitter:creator" content="@nextexchange"/>
    <meta name="twitter:title" content="NEXT.exchange - Decentralized trading platform for cryptocurrencies and ICO tokensale"/>
    <meta name="twitter:url" content="https://next.exchange/"/>
    <meta name="twitter:description" content="Decentralized stock exchange platform for trading fiat, cryptocurrencies, ICO, tokens, funds and digital assets. Join our tokensale!"/>
    <meta name="twitter:image" content="https://next.exchange/uploads/social-og.png"/>
    <meta name="twitter:image:alt" content="NEXT - Decentralized stock exchange platform for trading fiat, cryptocurrencies, ICO, tokens."/>

    <meta name="google-site-verification" content="EDj_I6pXN2QeXQDxfYu50_R0MTmVZ51B1CNbanz3J8U" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/all.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
{{--    <link rel="stylesheet" href="{{ asset('truffle/dist/assets/css/vendors.min.css') }}"/>--}}

    <link rel="stylesheet" href="{{ asset('css/home.css' )}}" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    @yield('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    @yield('scripts_header')
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">

@include('_partials.above-navbar-alert')

@if(Request::is('/'))

    <!--
    <div id="announcement">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <center class="cookies-text"><i class="fa fa-bullhorn" aria-hidden="true"></i>&nbsp;<b>At this moment we are moving our servers for the tokenholders launch. Website can switch into maintenance mode.</b></center>
            </div>
        </div>
    </div>
    </div>
    -->

@endif

@yield('content')

@if (Route::getCurrentRoute()->getName() !== 'vue-dashboard')
    @include('_layouts.footer_new')
@endif

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{ asset('/js/all.js') }}"></script>

@stack('scripts')
@yield('scripts_footer')

<!-- Start of LiveChat (www.livechatinc.com) code -->
<script type="text/javascript">
    window.__lc = window.__lc || {};
    window.__lc.license = 9253750;
    (function() {
        var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
        lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
    })();
</script>
<!-- End of LiveChat code -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-47307627-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-47307627-8');
</script>


</body>
</html>
