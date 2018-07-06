<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <title>NEXT.exchange - Decentralized trading platform for cryptocurrencies and ICO tokensale</title>
    <!--[if lt IE 9]> <link href= "css/ie8.css" rel= "stylesheet" media= "all" /> <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('truffle/dist/assets/css/vendors.min.css') }}"/>
    <link rel="stylesheet" href="{{asset('css/home.css')}}" type="text/css" />

    <!-- http://chartjs.org/ -->
</head>

<body>
<div class="page">
    <div class="modal__overflow">
        <div class="modal__window">
            <div class="modal__window__content">
                <h3>Next Smart Contract Address</h3>
                <div id="smartContractAddress" title='Copy to clipboard'>0x4e005a760e00e17c4912a8070eec047cfecbabbb
                </div>
                <div>Copy this address to your wallet to view your NEXT coins.</div>
                <div>Do not send funds to this address.</div>
            </div>
            <div class="modal__window__button"><i id='modalCloser' class="fa fa-times-circle" aria-hidden="true"></i>
            </div>
        </div>

        <div class="modal__snackbar">Copied to clipboard</div>
    </div>

    <div class="header-fake">
        <div class="menu-button1"></div>
        <div class="menu-hide1">

            <div class="menu-hide1__top"><a href="/" class="logo"><img src="img/logo2.png" alt=""></a></div>
            @if(Auth::check())
                <div class="setting-account2">
                    <div class="setting-account2__current">
                        <img src="{{ Gravatar::get(Auth::user()->email) }}" alt="">
                        <div class="setting-account2__text">
                            <div class="setting-account2__vertical">{{ Auth::user()->name }}</div>
                        </div>
                    </div>
                    <div class="setting-account2__drop">
                        <ul class="menu5">
                            <li class="menu5__item"><a href="/home">Members Area</a></li>
                            <li class="menu5__item"><a href="/profile">Edit Profile</a></li>
                            <li class="menu5__item"><a href="/id-proof">Upload ID Proof</a></li>
                            <li class="menu5__item line"><a href="/logout">Log Out</a></li>
                        </ul>
                    </div>
                </div>
                <div class="line-mob1"></div>
            @endif
            <ul class="menu3">
                @if(auth()->user())
                    <li class="menu3__item"><a href="/markets">EXCHANGE</a></li>
                @endif
                <li class="menu3__item"><a href="/community">COMMUNITY</a></li>
                <li class="menu3__item"><a href="/tokenmarket">TOKENMARKET</a></li>
                <li class="menu3__item"><a href="/suggestions">SUGGESTIONS</a></li>
                <li class="menu3__item"><a href="https://medium.com/nextexchange">BLOG</a></li>
                <li class="menu3__item"><a href="{{ url('contact') }}">CONTACT</a></li>
            </ul>
            <div class="link-account mod1">
                @if(!Auth::check())
                    <a href="{{ url('login') }}" class="style1">Login</a>
                    @if(!Request::is('register'))
                        <a href="{{ url('register') }}" class="style2">Register</a>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- HEADER: DESKTOP -->
    <div class="content-page">
        <div class="block1">
            <div class="block1__background"></div>
            <div class="header">
                <div class="page-line mod1">
                    <div class="header__left"><a href="/" class="logo"><img src="img/logo.png" alt=""
                                                                            style="width: 200px"></a></div>
                    <div class="header__right">
                        <ul class="menu1">
                            @if(Auth::check() && Auth::user()->hasRole('admin'))
                                <li class="menu1__item"><a href="/users">ADMIN</a></li>
                            @endif
                            @if(auth()->user())
                                <li class="menu1__item"><a href="/markets">EXCHANGE</a></li>
                            @endif
                            <li class="menu1__item"><a href="/community">COMMUNITY</a></li>
                            <li class="menu1__item"><a href="/tokenmarket">TOKENMARKET</a></li>
                            <li class="menu1__item"><a href="/suggestions">SUGGESTIONS</a></li>
                            <li class="menu1__item"><a href="https://medium.com/nextexchange">BLOG</a></li>
                            <li class="menu1__item"><a href="{{ url('contact') }}">CONTACT</a></li>
                        </ul>

                        @if(!Auth::check())
                            <div class="link-account">
                                <a href="{{ url('login') }}" class="style1">Login</a>
                                @if(!Request::is('register'))
                                    <a href="{{ url('register') }}" class="style2">Register</a>
                                @endif
                            </div>
                        @else
                            <div class="setting-account1">
                                <div class="setting-account1__current"><img src="{{ Gravatar::get(Auth::user()->email) }}" alt=""></div>
                                <div class="setting-account1__drop">
                                    <div class="setting-account1__title">{{ Auth::user()->name }}</div>
                                    <ul class="menu4">
                                        <li class="menu4__item"><a href="/home">Members Area</a></li>
                                        <li class="menu4__item"><a href="/profile">Edit Profile</a></li>
                                        <li class="menu4__item"><a href="/id-proof">Upload ID Proof</a></li>
                                        <li class="menu4__item line"><a href="/logout">Log Out</a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="page-line">
                <div class="text1">
                    <div class="text1__right">
                        <div class="slider1">
                            <div class="slider1__item"><img src="img/t1.jpg" alt=""></div>
                            <div class="slider1__item"><img src="img/t2.jpg" alt=""></div>
                            <div class="slider1__item"><img src="img/t3.jpg" alt=""></div>
                        </div>
                    </div>
                    <div class="text1__left">
                        <div class="text1__title">Hi
                            @if(@Auth::user()->name)
                                {{ Auth::user()->name }},
                            @else
                                there,
                            @endif
                            we're <span>NEXT.exchange</span> <br>a hybrid decentralized cryptocurrency exchange
                        </div>
                        <p class="f16" style="color: #80859e; font-size:16px; line-height: 26px; font-weight: 400">Join
                            us and trade your favorite cryptocurrencies and tokens in a fast and secure way. We have
                            fully paired Electroneum, Verge, Rupee, Electra and over 100+ ERC20 tokens with other crypto
                            and fiat.</p>
                    </div>
                    <div class="ticker">NEXT is listed on: <a target=_blank href="https://idex.market/eth/next">IDEX</a>
                        <span class="ticker_price"> <b>{{ $ticker->last }}</b> ETH/NEXT</span>
                    </div>
                    <div class="ticker">NEXT Smart Contract Address <i id="modalOpener"
                                                                       class="fa fa-info-circle ticker_icon"
                                                                       aria-hidden="true"></i></div>
                </div>
                <div class="over-content1">
                    <div class="over-content1__left">
                        <div class="button-left">
                            <a href="/whitepaper" class="button-left__style1">Get the Whitepaper</a>
                            <a href="https://goo.gl/forms/4gTBMVA2dk5C9GaJ3" class="button-left__style2">List your token on NEXT.exchange</a>
                        </div>
                    </div>
                    <div class="over-content1__right">
                        <a href="https://www.youtube.com/embed/ssxwc92NVZM?autoplay=1" class="video1 fancy"><b></b><span>Watch NEXT.exchange video</span><i>90 seconds</i></a>
                    </div>
                </div>
                <div class="social1">
                    <div><a href="https://www.linkedin.com/company/nextexchange/" class="icon1"></a></div>
                    <div><a href="https://nextexchange.slack.com" class="icon2"></a></div>
                    <div><a href="https://www.facebook.com/nextexchanger/" class="icon3"></a></div>
                    <div><a href="https://t.me/next_exchange" class="icon4"></a></div>
                    <div><a href="https://twitter.com/NextExchange" class="icon5"></a></div>
                    <div><a href="https://medium.com/nextexchange" class="icon6"></a></div>
                    <div><a href="javascript:void();" class="icon7"></a></div>
                    <div><a href="https://github.com/NextExchange" class="icon8"></a></div>
                </div>
            </div>
        </div>
        <div class="background1">
            <div class="page-line">
                <div class="logo-list1">
                    <div class="logo-list1__item">
                        <div class="logo-list1__over"><a href="https://foxico.io/project/nextexchange"><img src="img/e1-landing.png" alt="FoxICO"></a></div>
                    </div>
                    <div class="logo-list1__item">
                        <div class="logo-list1__over"><a href="https://wiserico.com"><img src="img/e2-landing.png" alt="WiserICO"></a></div>
                    </div>
                    <div class="logo-list1__item">
                        <div class="logo-list1__over"><a href="https://bitsify.net/ico-list/next-multi-asset-multi-feature-exchange/"><img src="img/e3-landing.png" alt="Bitsify"></a></div>
                    </div>
                    <div class="logo-list1__item">
                        <div class="logo-list1__over"><a href="https://www.listico.io/ico-calendar/next-exchange-next-gen-decentralized-stock-exchange"><img src="img/e4-landing.png" alt="ListICO"></a></div>
                    </div>
                    <div class="logo-list1__item">
                        <div class="logo-list1__over"><a href="https://www.kriptoparahaber.com/yeni-nesil-kripto-para-alim-satim-platformu-next-exchange-icoya-aciliyor.html"><img src="img/e5-landing.png" alt="Kriptoparahaber"></a></div>
                    </div>
                    <div class="logo-list1__item">
                        <div class="logo-list1__over"><a href="https://icobench.com/ico/next-exchange"><img src="img/e6-landing.png" alt="NextExchange ICObench"></a></div>
                    </div>
                </div>
                <div class="list-block1">
                    <div class="list-block1__item">
                        <div class="list-block1__over">
                            <div class="list-block1__text">
                                <div class="list-block1__icon"><img src="img/w1-landing.png" alt=""></div>
                                <div class="list-block1__title">More than generic tokens</div>
                                <p>The tokens can be used to power transactions on a wide class of different assets which are all tradable on NEXT.exchange.</p>
                            </div>
                        </div>
                    </div>
                    <div class="list-block1__item">
                        <div class="list-block1__over">
                            <div class="list-block1__text">
                                <div class="list-block1__icon"><img src="img/w2-landing.png" alt=""></div>
                                <div class="list-block1__title">Crypto Fiat</div>
                                <p>Only NEXT tokenholders have the ability to trade between crypto and fiat (for
                                    example, BTC/EUR or ETH/USD) for maximum flexibility.</p>
                            </div>
                        </div>
                    </div>
                    <div class="list-block1__item">
                        <div class="list-block1__over">
                            <div class="list-block1__text">
                                <div class="list-block1__icon"><img src="img/w3-landing.png" alt=""></div>
                                <div class="list-block1__title">Early access</div>
                                <p>Tokenholders get the exclusivity to trade new listings up to 14 days earlier.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="background2">
            <div class="page-line">
                <div class="text2">
                    <div class="text2__title">What are the benefits of using NEXT.exchange</div>
                    <p>We believe that anybody can make substantial profits in the cryptocurrency and digital asset market. This is exactly why we developed the best exchange with the most unique features.</p>
                </div>
                <div class="hide-mobile1">
                    <div class="nav-tab1">
                        <div class="nav-tab1__item active">Crypto Pool Trading</div>
                        <div class="nav-tab1__item">Community Trading</div>
                        <div class="nav-tab1__item">Crypto Mining</div>
                        <div class="nav-tab1__item">ICO Profiling</div>
                        <div class="nav-tab1__item">Investments Funds</div>
                        <div class="nav-tab1__item">Referral programs</div>
                    </div>
                    <div class="content-tab1">
                        <div class="content-tab1__item active">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d1.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Our platform is capable of obtaining high trading volumes and generating substantial profits. Pool trading helps spread your risk while investing in the top 30 cryptocurrencies on the market.</div>
                                </div>
                            </div>
                        </div>
                        <div class="content-tab1__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d2.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Our platform provides users with the ability to upload their own smart contracts in order to participate in a very active and thriving smart contract community for trading and increased exposure.</div>
                                </div>
                            </div>
                        </div>
                        <div class="content-tab1__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d3.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Users will be granted the option of investing their funds in multiple mining pools. Mining pools operate on a constant, passive earning model. Returns on investments from our mining pools will be split amongst investors.</div>
                                </div>
                            </div>
                        </div>
                        <div class="content-tab1__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d4.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">All of the ICO’s listed on our platform will have their own profile, through which the community at NEXT.exchange can easily get in touch with the team behind the ICO, as well as enjoy direct updates and news from them. Kind of like Facebook, imagine a social profile for ICO’s, we like to call it the ICO-book.</div>
                                </div>
                            </div>
                        </div>
                        <div class="content-tab1__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d5.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">NEXT.exchange will offer its users fully managed investment funds. Generally, pools of small-sized companies or ICO’s. Additionally, users won’t need to second guess or go through hassles when deciding when to buy or sell.</div>
                                </div>
                            </div>
                        </div>
                        <div class="content-tab1__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d6.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Our platforms referral program is designed to benefit both, the referred and referrers. A community bonus system will soon be implemented. Expect low fees, or even no fees for your hard work, as well as other bonuses.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="show-mobile1">
                    <div class="slider2">
                        <div class="slider2__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d1.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Our platform is capable of obtaining high trading volumes and generating substantial profits. Pool trading helps spread your risk while investing in the top 30 cryptocurrencies on the market.</div>
                                </div>
                            </div>
                        </div>
                        <div class="slider2__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d2.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Our platform provides users with the ability to upload their own smart contracts in order to participate in a very active and thriving smart contract community for trading and increased exposure.</div>
                                </div>
                            </div>
                        </div>
                        <div class="slider2__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d3.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Users will be granted the option of investing their funds in multiple mining pools. Mining pools operate on a constant, passive earning model. Returns on investments from our mining pools will be split amongst investors.</div>
                                </div>
                            </div>
                        </div>
                        <div class="slider2__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d4.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">All of the ICO’s listed on our platform will have their own profile, through which the community at NEXT.exchange can easily get in touch with the team behind the ICO, as well as enjoy direct updates and news from them. Kind of like Facebook, imagine a social profile for ICO’s, we like to call it the ICO-book.</div>
                                </div>
                            </div>
                        </div>
                        <div class="slider2__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d5.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">NEXT.exchange will offer its users fully managed investment funds. Generally, pools of small-sized companies or ICO’s. Additionally, users won’t need to second guess or go through hassles when deciding when to buy or sell.</div>
                                </div>
                            </div>
                        </div>
                        <div class="slider2__item">
                            <div class="text-small">
                                <div class="text-small__left"><div><img src="img/d6.png" alt=""></div></div>
                                <div class="text-small__over">
                                    <div class="text-small__vertical">Our platforms referral program is designed to benefit both, the referred and referrers. A community bonus system will soon be implemented. Expect low fees, or even no fees for your hard work, as well as other bonuses.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-line">
            <div class="list-icon1">
                <div class="list-icon1__item">
                    <div class="list-icon1__over">
                        <img src="img/q1.png" alt="">
                        <div class="list-icon1__title">Blockchain Built</div>
                        <p>Secure and immutable receipts accompany every transaction to ensure full transparency though the use of Blockchain tech.</p>
                    </div>
                </div>
                <div class="list-icon1__item">
                    <div class="list-icon1__over">
                        <img src="img/q2.png" alt="">
                        <div class="list-icon1__title">ICO launches</div>
                        <p>Deploy a smart contract in minutes. Add new and existing funds and trade them instantly on NEXT.Exchange.</p>
                    </div>
                </div>
                <div class="list-icon1__item">
                    <div class="list-icon1__over">
                        <img src="img/q3.png" alt="">
                        <div class="list-icon1__title">Multi-Asset Trading</div>
                        <p>Peer to peer trading platform that allows individuals and institutions to trade between fiat, crypto and other digital assets.</p>
                    </div>
                </div>
                <div class="list-icon1__item">
                    <div class="list-icon1__over">
                        <img src="img/q4.png" alt="">
                        <div class="list-icon1__title">Regulation Compliant</div>
                        <p>We are an established Dutch company that is in line with regulations and compliances. We won't ever go down.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="background3">

            <div class="page-line">
                <div class="title1">Top market cap</div>
                <div class="carousel1">

                    @include('pages.market-cap-ajax-block')

                </div>
            </div>

            <div class="background4">
                <div class="page-line">
                    <div class="text3">
                        <div class="text3__title">Meet the Team</div>
                        <p>Mean and clean, no overhead. Only successful entrepreneurs and business professionals with a proven track-record. Want to help us on our path towards a financial revolution? Then let's talk.</p>
                    </div>
                    <div class="hide-mobile1">
                        <div class="list-people1">
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p1.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Christiaan <br>van Steenbergen</div>
                                    <p>Serial Entrepreneur & Founder NEXT.exchange</p>
                                    <a href="https://www.linkedin.com/in/christiaanvansteenbergen" class="link-in"></a>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p2.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Gleb Jout</div>
                                    <p>Head of PR, <br>Marketing & Communications</p>
                                    <a href="https://www.linkedin.com/in/glebj" class="link-in"></a>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p3.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Drs. Chris Haveman</div>
                                    <p>Board advisor & Business <br>Strategy Operations</p>
                                    <a href="https://www.linkedin.com/in/chrishaveman" class="link-in"></a>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p4.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Mr. Rob van Dijk</div>
                                    <p>Laywer & Head <br>of Legal Operations</p>
                                    <a href="https://www.linkedin.com/in/rob-van-dijk-52a2296" class="link-in"></a>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p8.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Shabir Yunos<br></div>
                                    <p>Social Community & Support Manager on Telegram, Twitter and Facebook.</p>
                                    <a href="https://www.linkedin.com/in/shabiryunos/" class="link-in"></a>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p7.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Andrew Massman</div>
                                    <p>Blockchain and Senior Fullstack Tech Lead. Over 9 years of software development
                                        experience. 5 years of experience in Blockchain for BTC, ETH, XRP, Stellar,
                                        Monero, Hyperledger and forking.</p>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p5.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Orgest Shahaj</div>
                                    <p>Senior front- and back end developer with strong blockchain experience.</p>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p6.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Alina Suprun</div>
                                    <p>Over 5 years of design experience. Notable affiliations include: Reebok, Nestle,
                                        TELE2, SAMSUNG, Adidas, Aeroflot, Bayer, Kia.</p>
                                </div>
                            </div>
                            <div class="clear"><br><br></div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/blanco-avatar-man.gif" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Gleb Kovalev</div>
                                    <p>CTO of Popmechanic. Moscow State University, Faculty of Computational Mathematics
                                        and Cybernetics. Ex-Yandex Developer.</p>
                                    <a href="https://www.linkedin.com/in/zlebnik/" class="link-in"></a>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p9.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Kapiton Smirnov</div>
                                    <p>CEO/Co-founder of Popmechanic. SaaS company with clients from small business to
                                        big international corporations. Expert in digital marketing strategy and product
                                        building.</p>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/p10.png" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Nicholas Bucspun</div>
                                    <p>Financial industry professional. Experienced in both client-facing and backend
                                        operations. Native Spanish speaker.</p>
                                </div>
                            </div>
                            <div class="list-people1__item">
                                <div class="list-people1__over">
                                    <img src="img/blanco-avatar-man.gif" alt="" class="list-people1__foto">
                                    <div class="list-people1__title">Mr. X</div>
                                    <p>You want to become a team member of NEXT? Please send a motivated email to
                                        info@next.exchange</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="show-mobile1">
                        <div class="slider3">
                            <div class="slider3__item">
                                <div class="slider3__over">
                                    <img src="img/p1.png" alt="" class="slider3__foto">
                                    <div class="slider3__title">Christiaan <br>van Steenbergen</div>
                                    <p>Founder NEXT.exchange</p>
                                    <a href="" class="link-in"></a>
                                </div>
                            </div>
                            <div class="slider3__item">
                                <div class="slider3__over">
                                    <img src="img/p2.png" alt="" class="slider3__foto">
                                    <div class="slider3__title">Gleb Jout</div>
                                    <p>Blockchain Consultant, <br>Content & Strategy Manager</p>
                                    <a href="" class="link-in"></a>
                                </div>
                            </div>
                            <div class="slider3__item">
                                <div class="slider3__over">
                                    <img src="img/p3.png" alt="" class="slider3__foto">
                                    <div class="slider3__title">Drs. Chris Haveman</div>
                                    <p>Board advisor & Business <br>Strategy Operations</p>
                                    <a href="" class="link-in"></a>
                                </div>
                            </div>
                            <div class="slider3__item">
                                <div class="slider3__over">
                                    <img src="img/p4.png" alt="" class="slider3__foto">
                                    <div class="slider3__title">Mr. Rob van Dijk</div>
                                    <p>Laywer & Head <br>of Legal Operations</p>
                                    <a href="" class="link-in"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="decor-text1">
                        <div class="decor-text1__title1">+11</div>
                        <div class="decor-text1__title2">skilled developers</div>
                        <p>NEXT have decided not to reveal the names of the <br>developers in order to preserve safety.</p>
                    </div>
                    -->
                </div>
            </div>
            <div class="block-transform-animation1">
                <div class="particles">
                    <div id="particles-js1"></div>
                </div>
                <div class="block-transform-animation1__over">
                    <div class="page-line">
                        <div class="list-years1">
                            <div class="list-years1__line active">
                                <div class="list-years1__big-round"></div>
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">November 2nd, 2017</div>
                                                <p>Pre-ICO launch</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">November 20th, 2017</div>
                                                <p>Start Initial Coin Offering (ICO)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">November 25th, 2017</div>
                                                <p>Start of NEXT.exchange development</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">December 14th, 2017</div>
                                                <p>Showcase (alpha version) to tokenholders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">December 31th, 2017</div>
                                                <p>End Initial Coin Offering (ICO)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">January 1st, 2018</div>
                                                <p>Official registration of NEXT.exchange <br>in Dutch Chamber of Commerce</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">January 20th, 2018</div>
                                                <p>NEXT.exchange (beta) launch for tokenholders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">Q1 2018</div>
                                                <p>Start acquiring EU investment/e-money license <br>(in progress)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">May 7th, 2018</div>
                                                <p>Partial team reveal and new corporate website launch</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line diss">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">Q2 2018</div>
                                                <p>Launch of NEXT.exchange platform to the public</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line diss">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">Q3 2018</div>
                                                <p>Launch of NEXT.exchange Blockchain with <br>Investment Assets & Funds</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-years1__line diss">
                                <div class="list-years1__item">
                                    <div class="list-years1__over">
                                        <div class="list-years1__vertical">
                                            <div class="list-years1__block">
                                                <div class="list-years1__date">2019</div>
                                                <p>NEXT.exchange IPO on <a href="">EURONEXT</a> <br>and <a href="">OTC</a> Markets</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="background5">
            <div class="page-line">
                <div class="title2">Featured Coins</div>
                <div class="list-features-coins1">
                    <div class="list-features-coins1__item">
                        <div class="list-features-coins1__over">
                            <div class="list-features-coins1__logo"><img src="img/coin/64/BTC.png" alt="BTC"></div>
                            <p><b style="font-size: 120%; ">Bitcoin (BTC)</b><br><br> The worlds first and most popular
                                decentralized digital currency.</p>
                            <!--  <a href="" class="view-details1">View Details</a>
                             <a href="" class="all-link1"></a> -->
                        </div>
                    </div>
                    <div class="list-features-coins1__item">
                        <div class="list-features-coins1__over">
                            <div class="list-features-coins1__logo"><img src="img/coin/64/ETH.png" alt="ETH"></div>
                            <p><b style="font-size: 120%; ">Ethereum (ETH)</b><br><br> Decentralized, Immutable,
                                censorship-resistant and smart-contract based Blockchain platform.</p>
                            <!--  <a href="" class="view-details1">View Details</a>
                             <a href="" class="all-link1"></a> -->
                        </div>
                    </div>
                    <div class="list-features-coins1__item">
                        <div class="list-features-coins1__over">
                            <div class="list-features-coins1__logo"><img src="img/coin/64/NEXT.png" alt="NEXT"></div>
                            <p><b style="font-size: 120%; ">NEXT.exchange (NEXT)</b><br><br> A decentralized exchange
                                backed by a token and linked to every tradable digital asset.</p>
                            <!--  <a href="" class="view-details1">View Details</a>
                             <a href="" class="all-link1"></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="padding1">
            <div class="page-line">
                <div class="title1">Listing <br class="mobile">of New Coins</div>
                <div class="table-head-fake1">
                    <div class="table-head-fake1__item">Name</div>
                    <div class="table-head-fake1__item">Description</div>
                    <div class="table-head-fake1__item">Price</div>
                </div>
                <div class="table-body-fake1">
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">

                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="/img/coin/64/BDT.png" class="mod1"
                                                                             alt="Blockonix"></div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Blockonix (BDT)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Blockonix is the Most User-Friendly Decentralized Exchange for Ethereum-Based
                                        Tokens.
                                        Blockonix is a completely trustless exchange, and offers full ownership of the
                                        platform to its community of users. Blockonix allows users to trade ERC-20
                                        tokens from wallet to wallet.
                                    </p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/tokenmarket/BDT" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">

                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="/img/coin/64/KALI.png" class="mod1"
                                                                             alt="Kalicoin"></div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Kalicoin (KALI)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Kali Coin is mineable currency, developed to provide privileged services to early
                                        adopters at the CoinRecoil Exchange. The coin is developed over the scrypt
                                        algorithm. The early participants will enjoy free trading on their exchange for
                                        one full year along with priority customer service.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/KALI" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="/img/coin/64/COLX.png" alt="COLX">
                                    </div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">ColossusXT (COLX)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>The project carries a strong focus on decentralization, privacy, and real-world
                                        use. ColX utilizes the Proof-of-Stake 3.0 consensus protocol. The ultimate goal
                                        of ColossusXT is to be fast, secure, decentralized, and private. With the
                                        Colossus Grid, ColX aims to compete in the global market for distributed
                                        computing power and decentralized storage.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/COLX" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="/img/coin/64/SUMO.png" alt="Sumokoin">
                                    </div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Sumokoin (SUMO)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Sumokoin is a digital asset with privacy in mind, with Sumokoins Blockchain being
                                        highly resistant to analysis. All transactions are entirely untraceable, both
                                        sending and receiving addresses are fully encrypted, and amounts underlying
                                        transactions are obfuscated. This creates the inability to link Sumokoin
                                        transactions to a specific user or identity.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/SUMO" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/LYL.png" alt=""></div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">LoyalCoin (LYL)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Loyal acts as a replacement for classical customer loyalty programs by providing
                                        a flexible and fully transparent token which can be utilized to redeem rewards
                                        from any brands participating in the Loyal Coin ecosystem at any time and any
                                        place. The project aims to spur a more transparent and open ecosystem for
                                        consumer loyalty and relations between brands and their clients.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/LYL" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/CREA.png"
                                                                             alt="Creativechain"></div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Creativechain (CREA)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Creativechain is a marketplace of decentralized creative portfolios for the
                                        distribution and registration of multimedia content where the copyright and the
                                        distribution license of any digital art work are indelibly certified.
                                        The result is a transparent social network, without censorship or intermediaries
                                        specialized in the needs of creative communities and supported by its own public
                                        blockchain.
                                    </p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/CREA" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/ETN.png"
                                                                             alt="Electroneum"></div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Electroneum (ETN)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Electroneum is a digital asset designed to appeal to a market of over 2 billion
                                        smartphone users. ETN can be mined using one's smartphone, without the need to
                                        bare complex technical knowledge or previous experience in working with
                                        cryptocurrencies. Due to being held in digital form, users can send funds from
                                        one another the world over via their smartphones without the need to rely on an
                                        intermediary such as a bank. With transaction speeds going through in less than
                                        10 minutes.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/ETN" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/WYS.png" alt="Wysker">
                                    </div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Wysker (WYS)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Wysker is designed with consumers in mind, and more specifically how their data
                                        is handled. As is currently, consumers have little control over their data,
                                        while third-party intermediaries derive hefty financial gains from said data.
                                        The aim of WYS is to give power back to consumers in terms of how their own data
                                        is handled. </p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/WYS" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/SAFEX.png" alt="SAFEX">
                                    </div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Safe Exchange Coin (SAFEX)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Safex is a decentralized marketplace built on the blockchain, designed to bridge
                                        the gap between cryptocurrency and online commerce. People can now become part
                                        of an emerging, global marketplace where you can buy and sell products and
                                        services in a secure and safe environment directly on the blockchain, without
                                        ever having to hand over your payment details to a third party payment
                                        processor.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/SAFEX" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/TRAC.png"
                                                                             alt="Creativechain"></div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Origin Trail (TRAC)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Origin Trail is a decentralized protocol designed with the intent to share supply
                                        chain data over a transparent network. The project grants users the possibility
                                        to seamlessly track their goods and items throughout a supply chain with the
                                        simple click of a button. Enhances platform interoperability by promoting the
                                        defragmentation of complex data sets. TRAC users are also provided with
                                        cost-effective solutions for the governance of complex business ecosystems.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/TRAC" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/ECA.png" alt="Electra">
                                    </div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Electra (ECA)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Electra is a decentralized cryptocurrency utilizing a Proof-of-Work /
                                        Proof-of-Stake consensus system. The digital asset facilitates secure, fast, and
                                        extremely low fee peer to peer transactions. The ECA cryptocurrency is based on
                                        the secure NIST5 algorithm, allowing for transactions to be executed at higher
                                        speeds than in comparison to other assets within the crypto market.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/ECA" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/SKRP.png" alt="Skraps">
                                    </div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">SKRAPS (SKRP)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Skraps aims to make cryptocurrency investing as simple and fun as possible by
                                        eliminating cognitive investment burdens. Spare change left over after user
                                        transactions can be utilized for investment purposes within the SKRP platform
                                        through risk-adjusted portfolios. User spare change can be used for Initial Coin
                                        Offering (ICO) investing purposes.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/SKRP" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/REBBL.png"
                                                                             alt="Rebellious"></div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Rebellious (REBL)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Rebellious is a company that develops and deploys fast and secure Blockchain
                                        platforms for community utility tokens. Rebellious strives to create synergy
                                        between companies and their communities. Since a public Blockchain is not the
                                        ideal solution for every organization.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/REBL" class="all-link1"></a>
                        </div>
                    </div>
                    <div class="table-body-fake1__line">
                        <div class="table-body-fake1__over">
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="table-body-fake1__logo"><img src="img/coin/64/SKY.png" alt="SkyCoin">
                                    </div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Name</div>
                                    <div class="table-body-fake1__title1">Skycoin (SKY)</div>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Description</div>
                                    <p>Skycoin is an entire ecosystem where cryptocurrency is only part of the story.
                                        The rest includes the elimination of mining rewards, development of
                                        energy-efficient custom hardware, realization of transaction speeds that rival
                                        the likes of Visa, and the advancement of a more secure and private Internet
                                        alternative.</p>
                                </div>
                            </div>
                            <div class="table-body-fake1__item mod1">
                                <div class="table-body-fake1__vertical">
                                    <div class="title-hide1">Price</div>
                                    <div class="table-body-fake1__price"></div>
                                </div>
                            </div>
                            <a href="/orderbook/SKY" class="all-link1"></a>
                        </div>
                    </div>
                </div>

                <style>



                    .panel {
                        background-color: #fff;
                        border-radius: 10px;
                        padding: 15px 25px;
                        position: relative;
                        width: 100%;
                        z-index: 10;
                    }

                    .pricing-table {
                        box-shadow: 0px 10px 13px -6px rgba(0, 0, 0, 0.08), 0px 20px 31px 3px rgba(0, 0, 0, 0.09), 0px 8px 20px 7px rgba(0, 0, 0, 0.02);
                        display: flex;
                        flex-direction: column;
                    }

                    @media (min-width: 900px) {
                        .pricing-table {
                            flex-direction: row;
                        }
                    }

                    .pricing-table * {
                        text-align: center;
                        text-transform: uppercase;
                    }

                    .pricing-plan {
                        border-bottom: 1px solid #e1f1ff;
                        padding: 25px;
                    }

                    .pricing-plan:last-child {
                        border-bottom: none;
                    }

                    @media (min-width: 900px) {
                        .pricing-plan {
                            border-bottom: none;
                            border-right: 1px solid #e1f1ff;
                            flex-basis: 100%;
                            padding: 25px 50px;
                        }

                        .pricing-plan:last-child {
                            border-right: none;
                        }
                    }

                    .pricing-img {
                        margin-bottom: 25px;
                        max-width: 100%;
                    }

                    .pricing-header {
                        color: #111;
                        font-weight: 400;
                        letter-spacing: 1px;
                    }

                    .pricing-text {
                        color: #444;
                        font-weight: 400;
                        letter-spacing: 1px;
                        font-size: 14px;
                        margin-top: 20px;
                    }

                    .pricing-features {
                        color: #016FF9;
                        font-weight: 600;
                        letter-spacing: 1px;
                        margin: 50px 0 25px;
                    }

                    .pricing-features-item {
                        border-top: 1px solid #e1f1ff;
                        font-size: 12px;
                        line-height: 1.5;
                        padding: 15px 0;
                    }

                    .pricing-features-item:last-child {
                        border-bottom: 1px solid #e1f1ff;
                    }

                    .pricing-price {
                        color: #016FF9;
                        display: block;
                        font-size: 32px;
                        font-weight: 700;
                    }

                    .pricing-button {
                        border: 1px solid #9dd1ff;
                        border-radius: 10px;
                        color: #348EFE;
                        display: inline-block;
                        margin: 25px 0;
                        padding: 15px 35px;
                        text-decoration: none;
                        transition: all 150ms ease-in-out;
                    }

                    .pricing-button:hover,
                    .pricing-button:focus {
                        background-color: #e1f1ff;
                    }

                    .pricing-button.is-featured {
                        background-color: #48aaff;
                        color: #fff;
                    }

                    .pricing-button.is-featured:hover,
                    .pricing-button.is-featured:active {
                        background-color: #269aff;
                    }
                </style>

                <section>

                    <div class="title1">Apply <br class="mobile">your Coin to NEXT.exchange</div>

                    <div class="panel pricing-table">

                        <div class="pricing-plan">
                            <img src="/img/listing-free.png" alt="Free cryptocurrency listing" class="pricing-img"
                                 width="128" height="128">
                            <h2 class="pricing-header">Free</h2>
                            <p class="pricing-text">Ideal for new coins that want to get listed for free</p>
                            <ul class="pricing-features">
                                <li class="pricing-features-item">1000+ upvoting suggestions required</li>
                                <li class="pricing-features-item">Listing within 4 months</li>
                            </ul>
                            <span class="pricing-price">Free</span>

                        </div>

                        <div class="pricing-plan">
                            <img src="/img/listing-prio.png" alt="Cryptocurrency Priority listing" class="pricing-img"
                                 width="128" height="128">
                            <h2 class="pricing-header">Prio</h2>
                            <p class="pricing-text">Listing with priority, no queues</p>
                            <ul class="pricing-features">
                                <li class="pricing-features-item">Promotion with banner + social media</li>
                                <li class="pricing-features-item">Listing within 4 weeks</li>
                            </ul>
                            <span class="pricing-price">20 ETH</span>

                        </div>

                        <div class="pricing-plan">
                            <img src="/img/listing-premium.png" alt="Premium Cryptocurrency listing" class="pricing-img"
                                 width="128" height="128">
                            <h2 class="pricing-header">Premium</h2>
                            <p class="pricing-text">Only if you want the best for your community</p>
                            <ul class="pricing-features">
                                <li class="pricing-features-item">Same as PRIO + 100k traders introduction by email</li>
                                <li class="pricing-features-item">Featured coin on our homepage</li>
                            </ul>
                            <span class="pricing-price">50 ETH</span>

                        </div>

                    </div>

                <br>
                    <br>
                    <a href="https://goo.gl/forms/4gTBMVA2dk5C9GaJ3" class="apple-yout-listing1">Apply your listing</a>
            </section>
        </div>
        <div class="background6">
            <div class="page-line">
                <div class="title2">ICO launch</div>
                <div class="carousel2">
                    <div class="carousel2__item">
                        <div class="carousel2__over">
                            <div class="carousel2__logo"><img src="img/ico-mine.png" alt="" width="64" height="64">
                            </div>
                            <div class="carousel2__title">MINE (Q3 2018)</div>
                            <p>Invest in a state-of-the-art Dutch cryptomining facility with oil-cooled mining machines,
                                backed by NEXT.</p>
                        </div>
                    </div>
                    <div class="carousel2__item">
                        <div class="carousel2__over">
                            <div class="carousel2__logo"><img src="img/ico-coinsafe.png" width="64" height="64" alt="">
                            </div>
                            <div class="carousel2__title">Coinsafe (Q3 2018)</div>
                            <p>Cold-storage of tokens which are backed with fiat money, like USD, EUR, YEN, INR and
                                RUB.</p>
                        </div>
                    </div>

                </div>
                <a href="https://goo.gl/forms/YwQLIvZqrwCQ8Slz2" class="apple-yout-listing2">Apply your ICO launch</a>
            </div>
        </div>
        <div class="background7">
            <div class="particles">
                <div id="particles-js2"></div>
            </div>
            <div class="page-line">
                <div class="title1">FAQ</div>
                <div class="faq-list1">
                    <div class="faq-list1__item">
                        <div class="faq-list1__title" data-dt="0"><span>What is NEXT.exchange?</span></div>
                        <div class="faq-list1__hide">
                            <p>NEXT.exchange is a trademark of the Dutch registered company OpenTrader N.V. and is a
                                decentralized digital asset exchange for coins and tokens. Our mission is to make the
                                world of digital asset trading safer, convenient, enjoyable and trusted by taking all of
                                the steps necessary in order to be compliant with global regulatory frameworks, KYC /
                                AML policies and all other applicable legislations.</p>
                        </div>
                    </div>
                    <div class="faq-list1__item">
                        <div class="faq-list1__title" data-dt="0"><span>What distinguishes NEXT.exchange from other digital asset exchanges occupying this developing industry?</span>
                        </div>
                        <div class="faq-list1__hide">
                            <p>The NEXT.exchange platform patches up key issues that are common amongst other platforms
                                occupying the crypto-sphere. Most notably:
                            <br>
                                <ul>
                                    <li>- Zero fees imposed on new coin or token listings.</li>
                                    <li>- Lowest trading fees in the market.</li>
                                    <li>- Exceptional transaction speed and scalability through the use of new
                                        technologies.
                                    </li>
                                    <li>- Our own blockchain capable of providing over 100,000 transactions per
                                        second.
                                    </li>
                                    <li>- Instant ERC20 token integrations.</li>
                                    <li>- Listing of non-ERC20 blockchains. Such as Electroneum and Skycoin.</li>
                                    <li>- BTC, ETH, LTC, NEXT and fiat pairings</li>
                                    <li>- Registered as an official Dutch limited liabilty company.</li>
                                    <li>- Audited by one of the Big 4 Accounting firms.</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <div class="faq-list1__item">
                        <div class="faq-list1__title" data-dt="0"><span>What is the NEXT.exchange token?</span></div>
                        <div class="faq-list1__hide">
                            <p>The NEXT token is a ERC20 utility token. Without the use of which many of the platforms
                                key features are inaccessible to users. A user balance of over 100 NEXT tokens grants
                                access to the following platform features:
                                <br>
                            <ul>
                                <li>- Access to fiat operations (KYC approval required).</li>
                                <li>- Up to 2 weeks priority trading on new asset listings.</li>
                                <li>- Profit sharing (from trading fees and listings).</li>
                                <li>- Minimum 50% transaction fee reduction.</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <div class="faq-list1__item">
                        <div class="faq-list1__title" data-dt="0"><span>What are the platforms trading fees?</span></div>
                        <div class="faq-list1__hide">
                            <p>Trading fee without NEXT: 0.25%</p>
                            <p>Trading fee with NEXT: 0.05%</p>
                        </div>
                    </div>
                    <div class="faq-list1__item">
                        <div class="faq-list1__title" data-dt="0"><span>Will the NEXT.exchange platform be available for mobile devices?</span>
                        </div>
                        <div class="faq-list1__hide">
                            <p>Yes. The development of iOS and Android applications have already been started. Expected
                                to by released by Q3 2018.</p>
                        </div>
                    </div>
                    <div class="faq-list1__item">
                        <div class="faq-list1__title" data-dt="0"><span>On what basis do new projects become planned for listing?</span></div>
                        <div class="faq-list1__hide">
                            <p>We require all projects to be backed by their respective communities via a voting process.<br>
                                Once a submission reaches 1000 votes our team will evaluate the possibility of listing on our platform (no fees imposed).
                                Once a submission becomes labeled as “planned” we will provide respective teams with the necessary details for integration on our platform.
                            </p>
                        </div>
                    </div>
                    <div class="faq-list1__item">
                        <div class="faq-list1__title" data-dt="0"><span>Will there be future airdrops of NEXT?</span>
                        </div>
                        <div class="faq-list1__hide">
                            <p>No airdrops are planned nor active.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <style>
                .img-center {
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                }
            </style>
        <div class="padding2">
            <div class="page-line">
                <img src="/img/exchange-grandma.png" width="192" height="192" class="img-center"><br><br>
                <div class="text4">
                    <div class="text4__title1">We are building the most easy to use interface, so even grandma can
                        exchange her favorite token on NEXT.exchange.
                    </div>
                    <!-- <div class="text4__title2">Want to get involved in the beta launch of NEXT.exchange?</div>
                     <div class="text4__title3">To get early access, you need to be NEXT tokenholder.</div>
                     <a href="" class="apple-for-early-access1">Apply for early access</a> -->
                </div>
            </div>
        </div>

        @include('_layouts.footer')

    </div>
</div>

<script type="text/javascript" src="{{asset('js/home.js')}}"></script>
<script type="text/javascript" src="{{asset('js/common.js')}}"></script>
<script type="text/javascript">
    function refreshTable() {
        $.ajax({
            url: "top_market_cap_block",
            type: 'GET',
            success: function (data) {
                $('.carousel1').slick('removeSlide', null, null, true).slick('slickAdd',data);
            }
        });

    }
    $(document).ready(function () {
        setInterval(refreshTable, 60000);
    });

    // Modal window
    $("#modalOpener").click(function () {
        $(".modal__overflow").css('display', 'flex');
    });
    $(document).mouseup(function (e) {
        var elem = $('.modal__window__content');
        if (!elem.is(e.target) && elem.has(e.target).length === 0) {
            $(".modal__overflow").css('display', 'none');
        }
    });
    $("#smartContractAddress").click(function () {
        var value = $(this).text();
        copyToClipboard(value);
        showSnackbar();
    });

    function copyToClipboard(value) {
        var fakeTextarea = document.createElement('textarea');
        document.body.appendChild(fakeTextarea);
        fakeTextarea.value = value;
        fakeTextarea.select();
        document.execCommand('copy');
        fakeTextarea.parentNode.removeChild(fakeTextarea);
    }

    function showSnackbar() {
        var x = document.getElementsByClassName('modal__snackbar')[0];
        x.className = "modal__snackbar modal__snackbar_show";
        setTimeout(function () {
            x.className = x.className.replace("modal__snackbar_show", "");
        }, 750);
    }


</script>

</body>
</html>