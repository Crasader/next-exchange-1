
<?php
// Set attributes for homepage
if(Request::is('/')) {
    $logo = asset('/img/next-exchange-logo-white.png');
    $class_header = 'transparent';
}
else {
    $logo = asset('/img/logo.png');
    $class_header = 'default';
}
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

    .default ul li a.active {
        color: #2ebdf7;
        font-size: 80%;
        background-color: #edf9ff;
        padding: 0px 3px 0px 3px;
        border-radius: 5px;
    }

    /* .navbar-user {
        position: relative;
    } */
    /* .navbar-user a span img {
        margin-top: -5px;
    } */

    /* .navbar-user ul.dropdown-menu {
        margin-top: 24px !important;
        line-height: 26px;
        box-shadow: none;
        border-radius: 0px 0px 5px 5px;
    } */
</style>

<?php
$sUri = Request::segment(1);

if ($sUri == 'exchange') $active_1 = 'active';
if ($sUri == 'community') $active_2 = 'active';
if ($sUri == 'tokenmarket') $active_3 = 'active';
if ($sUri == 'suggestions') $active_4 = 'active';
if ($sUri == 'contact') $active_5 = 'active';
if ($sUri == 'login') $active_6 = 'active';
if ($sUri == 'register') $active_7 = 'active';

?>

<!-- HEADER: SUBPAGE -->
<header class="<?= $class_header ?>" id="index_header" style="border-bottom: 1px solid #ebebeb; height: 75px;">
    <nav class=" container myNavContainer"  >
        <a class="logo myNavLogo"  href="/" style="padding-top:25px;">
            <img src="<?= $logo ?>" style="padding-top: 6px; border-radius: 0px;"
                 alt="Next Decentralized Cryptocurrency Exchange ">
        </a>

        <!-- <div class="menu-icon"><span></span></div>
        <ul class="menu">
            @if(Auth::check() && Auth::user()->hasRole('admin'))
            <li class="dropdown navbar-user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    Admin <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" style="z-index: auto;">
                    <li {{ Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'class=active' : null }}>{!! Html::link(url('/users'), Lang::get('titles.adminUserList')) !!}</li>
                    <li {{ Request::is('users/create') ? 'class=active' : null }}>{!! Html::link(url('/users/create'), Lang::get('titles.adminNewUser')) !!}</li>
                    {{-- <li {{ Request::is('themes','themes/create') ? 'class=active' : null }}>{!! Html::link(url('/themes'), Lang::get('titles.adminThemesList')) !!}</li> --}}
                    {{-- <li {{ Request::is('logs') ? 'class=active' : null }}>{!! Html::link(url('/logs'), Lang::get('titles.adminLogs')) !!}</li> --}}
                    {{-- <li {{ Request::is('activity') ? 'class=active' : null }}>{!! Html::link(url('/activity'), Lang::get('titles.adminActivity')) !!}</li> --}}
                    {{-- <li {{ Request::is('phpinfo') ? 'class=active' : null }}>{!! Html::link(url('/phpinfo'), Lang::get('titles.adminPHP')) !!}</li> --}}
                    <li {{ Request::is('routes') ? 'class=active' : null }}>{!! Html::link(url('/routes'), Lang::get('titles.adminRoutes')) !!}</li>
                    <li {{ Request::is('active-users') ? 'class=active' : null }}>{!! Html::link(url('/active-users'), Lang::get('titles.activeUsers')) !!}</li>
                </ul>
            </li>
            @endif
        </ul> -->
        <div class="menu-icon myNavIcon"><span></span></div>
        <ul class="menu myNavMenu">

            @if(auth()->user())
                <li>
                    <a class='{{ @$active_1 }}' href="/markets">Exchange</a>
                </li>
            @endif
            <li>
                <a class='{{ @$active_2 }}' href="/community">Community</a>
            </li>

            <li>
                <a class='{{ @$active_3 }}' href="/tokenmarket">Tokenmarket</a>
            </li>
            <li>
                <a class='{{ @$active_4 }}' href="/suggestions">Suggestions</a>
            </li>

            <li>
                <a href="https://medium.com/nextexchange">Blog</a>
            </li>
            <li>
                <a class='{{ @$active_5 }}' href="{{ url('contact') }}">Contact</a>
            </li>

            @if(!Auth::check())
                <li>
                    <a class='{{ @$active_6 }}' href="{{ url('login') }}">Login</a>
                </li>
                @if(!Request::is('register'))
                    <li >
                        <a class='{{ @$active_7 }}' href="{{ url('register') }}">Register</a>
                    </li>
                @endif
            @else
                <li class="dropdown navbar-user">
                    <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">

                        <span class="avatar avatar-home"><img class="myNavbarUserImg" src="{{ Gravatar::get(Auth::user()->email) }}" class="img-responsive" width="32px" alt="" /></span>
                        <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu myDropMenu" style="z-index: auto;">
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
    </nav>
</header>

