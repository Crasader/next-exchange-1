<?php

$logo = asset('/img/next-exchange-logo-sm.png');
$class_header = 'default';

?>

<style>
    .default ul li a {
        color: #1e282f;
        font-size: 80%;
    }

    .default ul li a:hover {
        color: #000;
        font-size: 80%;
    }
</style>

<?php
$sUri = Request::segment(1);

if ($sUri == 'markets') $active_1 = 'active';
if ($sUri == 'orderbook') $active_2 = 'active';
if ($sUri == 'wallet') $active_3 = 'active';
if ($sUri == 'transactions') $active_4 = 'active';

?>

<style>
    .layout-navbar {
        -webkit-box-shadow: 0 1px 0 rgba(24, 28, 33, 0.04);
        box-shadow: 0 1px 0 rgba(24, 28, 33, 0.04)
    }

    .navbar.bg-primary .navbar-text {
        color: #daf2ff
    }

    .navbar.bg-primary .navbar-text a {
        color: #fff
    }

    .navbar.bg-primary .navbar-text a:hover, .navbar.bg-primary .navbar-text a:focus {
        color: #fff
    }

    .navbar-search-box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin-top: 20px;
    }

    .navbar-search-box:not(.active) {
        cursor: pointer
    }

    .navbar-search-input {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        overflow: hidden;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        max-width: 0;
        -webkit-transition: max-width .3s ease-in-out;
        transition: max-width .3s ease-in-out
    }

    .navbar-search-box.active .navbar-search-input {
        max-width: 100vw
    }

    .logo img {
        width: 20px !important;
        height: 24px !important;
        margin-top: -2px;
    }

    /* .avatar-home img {
        margin-top: -3px !important;
    } */

    .search-group {
        display: flex;
        flex-direction: column;
    }
    .search-coins-list {
        position: absolute;
        display: block;
        overflow: auto;
        background: white;
        top: 75px;
        width: 200px;
        max-height: 300px;
    }
    .dropdown-item1 {
        line-height: 24px;
        border: 1px solid #ebebeb;
        border-top: 0;
        padding-top: 5px;
    }
    .dropdown-item1 img{
        margin: 0 10px;
        height: 16px;
        width: 16px;
    }

    .dropdown-item1 a div {
        text-align: center;
        padding: 0 10px;
    }

    .dropdown-item1 a div:hover {
        background: rgb(249, 249, 253);
    }

    .search-group img {
        vertical-align: middle;
    }

    .dropdown-item .btn-market {
        padding: 2px;
        margin-left: 6px;
    }

</style>


<!-- HEADER: SUBPAGE -->
<header class="<?= $class_header ?>" id="index_header" style="border-bottom: 1px solid #ebebeb; height: 75px;">
    <nav class=" container myNavContainer">
        <a class="logo myNavLogo myNavLogo2" href="/" style="padding-top:25px;">
            <img src="<?= $logo ?>" style="padding-top: 5px; border-radius: 0px;"
                 alt="Next Decentralized Cryptocurrency Exchange ">
        </a>
        <div class="menu-icon myNavIcon"><span></span></div>

        <ul class="menu myNavMenu myNavMenu-left">
            @if(auth()->user())
                <li class='{{ @$active_1 }}'>
                    <a href="/markets">Markets</a>
                </li>
                <li class='{{ @$active_2 }}'>
                    <a href="/orderbook">Orderbook</a>
                </li>
                <li class='{{ @$active_3 }}'>
                    <a href="/wallet">Wallet</a>
                </li>
                <li class='{{ @$active_4 }}'>
                    <a href="/transactions">Transactions</a>
                </li>
            @endif
        </ul>


        <ul class="menu myNavMenu">
            @if(!Auth::check())
                <li class='{{ @$active_6 }}'>
                    <a href="{{ url('login') }}">Login</a>
                </li>

                @if(!Request::is('register'))
                    <li class='{{ @$active_7 }}'>
                        <a href="{{ url('register') }}">Register</a>
                    </li>
                @endif
            @else
                <li class="dropdown navbar-user">
                    <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">

                        <span class="avatar avatar-home"><img class="myNavbarUserImg" src="{{ Gravatar::get(Auth::user()->email) }}"
                                                              class="img-responsive avatar-header" width="32px" alt=""/></span>
                        <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu myDropMenu2" style="z-index: auto;">
                        <li><a href="/home">Members area</a></li>
                        <li><a href="/profile">My Profile</a></li>
                        <li><a href="/id-proof">Upload Id Proof</a></li>
                        <!-- <li class="divider"></li> -->
                        <li><a href="/wallet">My Wallet</a></li>
                        <li><a href="/transactions">My Transactions</a></li>
                        <!-- <li class="divider"></li> -->
                        <li><a href="/logout">Log Out</a></li>
                    </ul>
                </li>
            @endif
        </ul>
        <span class="navbar-nav myNavSearchBar">
            <label class="nav-item navbar-text navbar-search-box p-0 active">
                <i class="fa fa-search navbar-icon align-middle"></i>
                <div class="navbar-search-input pl-2 search-group">
                  <input type="text" class="form-control navbar-text mx-2 nav-exchange-search" placeholder="Search..."
                         style="width:200px">
                    <div class="search-coins-list" style="display: none">
                    </div>
                </div>
            </label>
        </span>


    </nav>
</header>

<!-- HEADER: EXCHANGE
<header class="<?= $class_header ?>" id="index_header exchange" style="border-bottom: 1px solid #ebebeb; height: 75px;">
    <nav class=" container" >
        <a class="logo" href="/" style="padding-top:25px;">
            <img src="<?= $logo ?>" style="padding-top: 6px; border-radius: 0px;" alt="Next Exchange Stock Market Logo">
        </a>
        <div class="menu-icon"><span></span></div>
        <ul class="menu">
            @if(auth()->user())
    <li>
        <a href="/exchange/beta">Exchange</a>
    </li>
@endif
        <li>
            <a class='active' href="/social">Community</a>
        </li>

        <li>
            <a href="/tokenmarket">Tokenmarket</a>
        </li>
        <li>
            <a href="/suggestions">Suggestions</a>
{{-- <a href="https://nextexchange.featureupvote.com">Suggestions</a> --}}
        </li>

        <li>
            <a href="https://medium.com/nextexchange">Blog</a>
        </li>
        <li>
            <a href="{{ url('contact') }}">Contact</a>
            </li>

            @if(!Auth::check())
    <li>
        <a href="{{ url('login') }}">Login</a>
                </li>

                @if(!Request::is('register'))
        <li >
            <a href="{{ url('register') }}">Register</a>
                    </li>
                @endif

@else
    <li class="dropdown navbar-user">
        <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">

            <span class="avatar avatar-home"><img src="{{ Gravatar::get(Auth::user()->email) }}" class="img-responsive" width="32px" alt="" /></span>
                        <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" style="z-index: auto;">
                        <li><a href="/home">Members area</a></li>
                        <li><a href="/profile">Edit Profile</a></li>
                        <li><a href="/id-proof">Upload Id Proof</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout">Log Out</a></li>
                    </ul>
                </li>

            @endif
        </ul>
    </nav>
</header>

-->
<!--/Navigation

<header class="<?= $class_header ?>" id="index_header_scroll">
    <nav class=" container" >
        <a class="logo" href="/">
            <img src="<?= $logo ?>" alt="Next Exchange Stock Market Logo">
        </a>
        <div class="menu-icon"><span></span></div>
        <ul class="menu">
            <li>
                <a href="/community">Community</a>
            </li>

            <li>
                <a href="https://nextexchange.featureupvote.com">Suggestions</a>
            </li>

            <li>
                <a href="https://medium.com/nextexchange">Blog</a>
            </li>
            <li>
                <a href="{{ url('contact') }}">Contact</a>
            </li>

            @if(!Auth::check())
    <li>
        <a href="{{ url('login') }}">Login</a>
                </li>

                @if(!Request::is('register'))
        <li >
            <a href="{{ url('register') }}">Register</a>
                    </li>
                @endif

@else
    <li class="dropdown navbar-user">
        <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">

            <span class="avatar avatar-home"><img src="{{ Gravatar::get(Auth::user()->email) }}" class="img-responsive" width="32px" alt="" /></span>
                        <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" style="z-index: auto;">
                        <li><a href="/home">Members area</a></li>
                        <li><a href="/profile">Edit Profile</a></li>

                        <li class="divider"></li>
                        <li><a href="/logout">Log Out</a></li>
                    </ul>
                </li>

            @endif
        </ul>
    </nav>
</header>

-->

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('keyup', '.nav-exchange-search', function (e) {
        var query = e.target.value;
        if (query.length === 0) {
            return $('.search-coins-list').hide();
        }
        if (query.length <= 1) {
            return;
        }
        $.get('/coins/search?query=' + query, function (data) {
            $('.search-coins-list').show();
            $('.search-coins-list').html('');
            var coins = data.coins.data;
            var transactional = data.transactional_ables.data;
            for (var item of coins) {
                var content = '<img src="/img/coin/16/' + item.name + '.png"/>' + item.name + ' ';
                for (var trans of transactional) {
                    if (item.id === trans.id) continue;
                    content += '<a class="btn-market" href="/orderbook/' + item.name + '/' + trans.name + '"><div>' + trans.name + '</div></a>'
                }
                var coin = $('<div />')
                    .attr({class: 'dropdown-item1'})
                    .html(content);
                $('.search-coins-list')
                    .append(coin)
            }
        });
    });
</script>
