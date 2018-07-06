<style>
    .badge-dashboard { background-color: #000 !important; padding: 4px 6px 4px 6px; }
</style>



<aside class="left-sidebar" >
    <a class="logo" href="/"><img class="colors" src="/img/next-exchange-logo-orig.png" alt="Next Exchange Stock Market Logo" width="180" style="padding-left: 20px;">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">


                <li class="user-profile"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><img src="/img/23.jpg" alt="user" /><span class="hide-menu">{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}} </span></a>
                    <ul >
                        <li><a href="/profile">My Profile </a></li>
                        <li><a href="/tokens/buy/next"><b>Buy</b> NEXT tokens</a></li>

                        <li><a href="/tokens/add">List your <b>token</b></a></li>

                        <li><a href="/messages">Messages <span class="badge badge-dashboard pull-right">1</span></a></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </li>
                <li class="nav-devider"></li>
                <li class="nav-small-cap">PERSONAL</li>
                <li> <a class="has-arrow waves-effect waves-dark" href="/home" aria-expanded="false"><i class="icon-Cloud"></i><span class="hide-menu">Dashboard </span></a>

                </li>
                <li> <a class="has-arrow waves-effect waves-dark" href="/wallet" aria-expanded="false"><i class="icon-Coin"></i><span class="hide-menu">Wallet</span></a></li>
                <li> <a class="has-arrow waves-effect waves-dark" href="/exchange" aria-expanded="false"><i class="icon-Coins"></i><span class="hide-menu">Exchange</span></a></li>
                <li> <a class="has-arrow waves-effect waves-dark" href="/api" aria-expanded="false"><i class="icon-Coding"></i><span class="hide-menu">Api Development</span></a></li>



            </ul>
        </nav>
    </div>
</aside>