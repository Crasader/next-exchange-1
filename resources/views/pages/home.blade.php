@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')



    <div id="main-container">
        <section class="cover height-100 imagebg" data-gradient-bg="#0965a5,#04adfe,#32b5e5,#0965a5">
            <div class="background-image-holder"><img alt="background" src=""/></div>
            <div class="container pos-vertical-center">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                            <span class="h1 home-heading">
                               Hi there, we're NEXT.exchange<br>a hybrid decentralized community-driven cryptocurrency exchange
                            </span>
                        <p class="lead pt20 align-center">
                            Join us and trade your favorite cryptocurrencies and tokens in a fast and secure way.<br>
                            We have fully paired Electroneum, Verge, Rupee, Electra, and over 100+ ERC20 tokens with other crypto and fiat.
                           </p>


                        <div class="modal-instance block ptb20 model-link-parent">
                            <div class="modal-trigger">
                            <span class="color--white">
                                    <i class="fa fa-play-circle-o" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Watch NEXT.exchange video </strong>&nbsp;&nbsp;&nbsp;90 seconds
                            </span>
                            </div>

                            <div class="modal-container">
                                <div class="modal-content bg-dark" data-width="60%" data-height="60%">
                                    <iframe data-src="https://www.youtube.com/embed/ssxwc92NVZM?autoplay=1"
                                            allowfullscreen="allowfullscreen"></iframe>
                                </div>
                                <!--end of modal-content-->
                            </div>
                            <!--end of modal-container-->
                            
                            <br>
                            <!--
                            <span class="color--white">
                                    <i class="fa fa-rocket" aria-hidden="true"></i>&nbsp;&nbsp;<strong>AIRDROP NEXT.exchange</strong>&nbsp;&nbsp;&nbsp;<a href="https://goo.gl/forms/nCKaJ7NwTTXenKjF3" class="text-white">Click here</a>
                            </span>
                                -->
                        </div>

                        <div class="pt20"></div>

                        <div class="row home-buttons">
                            <div class="col-md-12 text-center"><a href="/whitepaper"
                                                      class="btn-outline home-call-to-action"
                                                      style="color:#fff !important;font-weight:700; background:#33b5e5"
                                                      target="_blank">Get the Whitepaper (PDF)</a>
								
													<a href="{{ url('/ico/listing') }}" class="btn-outline home-call-to-action"
                                                      style="color:#fff !important;font-weight:700; background:#202c34"
                                                      target="_blank">List your token on NEXT.exchange</a>
                            </div>
                         

                        </div>
                        <!--end of modal instance-->
                    </div>
                </div>
                <!--end of row-->
            </div>

            <div class="space-35"></div>

            <div class="container pos-absolute pos-bottom">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="channels">
                            <span><a href="https://www.linkedin.com/company/nextexchange/" target="_blank"><i
                                            class="fa fa-linkedin fa-2x"></i></a></span>
                            <span><a href="https://nextexchange.slack.com" target="_blank"><i
                                            class="fa fa-slack fa-2x"></i></a></span>
                            <span><a href="https://t.me/next_exchange" target="_blank"><i
                                            class="fa fa-telegram fa-2x"></i></a></span> <span><a
                                        href="https://www.facebook.com/nextexchanger/" target="_blank"><i
                                            class="fa fa-facebook fa-2x"></i></a></span> <span><a
                                        href="https://twitter.com/NextExchange" target="_blank"><i
                                            class="fa fa-twitter fa-2x"></i></a></span>
                            <span><a href="https://medium.com/nextexchange" target="_blank"><i
                                            class="fa fa-medium fa-2x"></i></a></span><span><a
                                        href="#"><i
                                            class="fa fa-btc fa-2x"></i></a></span>
                            <span><a href="https://github.com/NextExchange"><i
                                            class="fa fa-github fa-2x"></i></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="news" class="center bg--secondary ">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-md-3 col-lg-2"><span class="centerer"></span><a
                                href="https://foxico.io/project/nextexchange"><img class="img-fluid"
                                                                                   src="/img/ico-foxico.png"
                                                                                   alt="FoxICO"></a></div>
                    <div class="col-6 col-md-3 col-lg-2"><span class="centerer"></span><a
                                href="https://wiserico.com"><img class='img-greyscale img-fluid'
                                                                 src="/img/ico-wiserico.png" alt="WiserICO"></a></div>
                    <div class="col-6 col-md-3 col-lg-2"><span class="centerer"></span><a
                                href="https://bitsify.net/ico-list/next-multi-asset-multi-feature-exchange/"><img
                                    class='img-greyscale img-fluid' src="/img/ico-bitsify.png" alt="Bitsify"></a></div>
                    <div class="col-6 col-md-3 col-lg-2"><span class="centerer"></span><a
                                href="https://www.listico.io/ico-calendar/next-exchange-next-gen-decentralized-stock-exchange"><img
                                    class='img-greyscale img-fluid' src="/img/ico-listico.png" alt="ListICO"></a></div>
                    <div class="col-6 col-md-3 col-lg-2"><span class="centerer"></span><a
                                href="https://www.kriptoparahaber.com/yeni-nesil-kripto-para-alim-satim-platformu-next-exchange-icoya-aciliyor.html"><img
                                    class='img-greyscale img-fluid' src="/img/ico-kriptoparahaber.png"
                                    alt="Kriptoparahaber"></a></div>
                    <div class="col-6 col-md-3 col-lg-2"><span class="centerer"></span><a
                                href="https://icobench.com/ico/next-exchange"><img class='img-greyscale img-fluid'
                                                                            src="https://cartaxi.io/images/media/icobench.svg"
                                                                            alt="NextExchange ICObench"></a></div>
                </div>
            </div>
        </section>

        <section class="text-center bg--secondary pt20" id="tokens">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="feature feature-3 boxed boxed--lg boxed--border"><i
                                    class="icon icon-Compass-4 icon--lg"></i>
                            <h4>More than generic tokens</h4>
                            <p> The tokens can be used to power transactions on a wide class of different assets which
                                are all tradable on NEXT.exchange. </p>
                            <a href="#"> </a>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="feature feature-3 boxed boxed--lg boxed--border"><i
                                    class="icon icon-Coins icon--lg"></i>
                            <h4>Crypto <> Fiat</h4>
                            <p> Only tokenholders are able to trade between crypto and fiat (for example, BTC/EUR or
                                ETH/USD) to
                                maximize their
                                profits.</p>
                            <a href="#"> </a>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="feature feature-3 boxed boxed--lg boxed--border"><i
                                    class="icon icon-Bird icon--lg"></i>
                            <h4>Early access</h4>
                            <p> Tokenholders get the exclusivity to trade new listings up to 14 days earlier.</p>
                            <a href="#"> </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="text-center bg--secondary pt40">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 center">
                        <h2>What are the benefits of using <b>NEXT.exchange</b></h2>
                        <br>
                        <p class="lead">We believe that anybody can make substantial profits in the cryptocurrency and digital asset market. This is exactly why we developed the best exchange with the most unique features.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="text-center bg--secondary-gradient ptb40" id="features">
            <div class="container">
                <div class="row slides">
                    <ul class="slides">
                        <li class=" col-md-6">
                            <div class="feature feature-3 boxed boxed--lg boxed--border text-center">
                                <img alt="Crypto Mining Pool"
                                     src="{{ asset('/img/if_Crypto_Currency-01_2447416.svg') }}" height="92"
                                     class="icon icon--lg"/>
                                <h3>Crypto Pool Trading</h3>
                                <p> Our platform is capable of obtaining high trading volumes and generating substantial
                                    profits. Pool trading helps spread your risk while investing in the top 30
                                    cryptocurrencies on the market.

                                </p>
                            </div>
                            <!--end feature-->
                        </li>
                        <li class=" col-md-6">
                            <div class="feature feature-3 boxed boxed--lg boxed--border text-center">
                                <img alt="Community Trading" src="{{ asset('img/if_Management_770905.svg') }}"
                                     height="92"
                                     class="icon icon--lg"/>
                                <h3>Community Trading</h3>
                                <p>Our platform provides users with the ability to upload their own smart contracts in
                                    order to participate in a very active and thriving smart contract community for
                                    trading and increased exposure.
                                </p>

                            </div>
                            <!--end feature-->
                        </li>
                        <li class=" col-md-12">
                            <div class="feature feature-3 boxed boxed--lg boxed--border text-center">
                                <img alt="Crypto Mining Pool" src="{{ asset('img/if_25_1787945.svg') }}" height="92"
                                     class="icon icon--lg"/>
                                <h3>Crypto Mining</h3>
                                <p>
                                    Users will be granted the option of investing their funds in multiple mining pools.
                                    Mining pools operate on a constant, passive earning model. Returns on investments
                                    from our mining pools will be split amongst investors.

                                </p>

                            </div>
                            <!--end feature-->
                        </li>
                        <li class="col-md-12">
                            <div class="feature feature-3 boxed boxed--lg boxed--border text-center">
                                <img alt="ICO Profiling" src="{{ asset('img/if_scanning_1405966.svg') }}" height="92"
                                     class="icon icon--lg"/>
                                <h3>ICO Profiling</h3>

                                <p>
                                    All of the ICO’s listed on our platform will have their own profile, through which
                                    the community at NEXT.exchange can easily get in touch with the team behind the ICO,
                                    as well as enjoy direct updates and news from them.

                                    Kind of like Facebook, imagine a social profile for ICO’s, we like to call it the
                                    ICO-book.

                                </p>

                            </div>
                            <!--end feature-->
                        </li>
                        <li class=" col-md-6">
                            <div class="feature feature-3 boxed boxed--lg boxed--border text-center">
                                <img alt="Investments Funds" src="{{ asset('img/if_trends_333991.svg') }}" height="92"
                                     class="icon icon--lg"/>
                                <h3>Investments Funds</h3>
                                <p>
                                    NEXT.exchange will offer its users fully managed investment funds. Generally, pools
                                    of small-sized companies or ICO’s. Additionally, users won’t need to second guess or
                                    go through hassles when deciding when to buy or sell.

                                </p>
                            </div>
                            <!--end feature-->
                        </li>
                        <li class=" col-md-6">
                            <div class="feature feature-3 boxed boxed--lg boxed--border text-center">
                                <img alt="Referral program" src="{{ asset('img/if_conference_1250820.svg') }}"
                                     height="92"
                                     class="icon icon--lg"/>
                                <h3>Referral programs</h3>
                                <p>
                                    Our platforms referral program is designed to benefit both, the referred and
                                    referrers. A community bonus system will soon be implemented.
                                    Expect low fees, or even no fees for your hard work, as well as other bonuses.

                                </p>

                            </div>
                            <!--end feature-->
                        </li>
                    </ul>

                </div>
            </div>
        </section>


        <section class="bg--grey why_next ptb60">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="feature feature-6 ptb20"><i class="icon color--primary icon-Cube-Molecule icon--sm"></i>
                            <h4>Built on Blockchain</h4>
                            <p>Secure and immutable receipts accompany every transaction to ensure full transparency though the use of Blockchain tech.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="feature feature-6 ptb20"><i class="icon color--primary icon-Golf icon--sm"></i>
                            <h4>ICO launches</h4>
                            <p>Deploy a smart contract in minutes. Add new and existing funds and trade them instantly on NEXT.Exchange</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="feature feature-6 ptb20"><i class="icon color--primary icon-Arrow-Mix icon--sm"></i>
                            <h4>Multi-Asset Trading</h4>
                            <p> Peer to peer trading platform that allows individuals and institutions to trade between
                                fiat, crypto and other digital assets</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="feature feature-6 ptb20"><i class="icon color--primary icon-Pen icon--sm"></i>
                            <h4>Regulation Compliant</h4>
                            <p>We are an established Dutch company that is in line with regulations and compliances. We won't ever go down.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="ptb60 text-center">

            @include('pages.market-cap')

        </section>



        <section class="center team ptb60">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Meet the <b>Team</b></h2>
                        <p class="lead">
                            Mean and clean, no overhead. Only successful entrepreneurs and business professionals with a
                            proven track-record. Want to help us on our path towards a financial revolution? Then let's
                            talk.
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>


        <section class="text-center  pb40" id="team">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-md-3">
                        <div class="feature feature-8"><img alt="Image" class='rounded-circle'
                                                            src="{{ asset('/img/team-christiaan-van-steenbergen.png') }}"
                                                            width="60%"/>
                            <h4>Christiaan van Steenbergen</h4> <span>Founder NEXT.exchange</span>
                            <br><a href="https://www.linkedin.com/in/christiaanvansteenbergen/"><i
                                        class="fa fa-linkedin-square" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="feature feature-8"><img alt="Image" class='rounded-circle'
                                                            src="{{ asset('/img/team-gleb-jout.png') }}" width="60%"/>
                            <h4>Gleb Jout</h4> <span>Blockchain Consultant, Content & Strategy Manager</span>
                            <br><a href="https://www.linkedin.com/in/glebj/"><i class="fa fa-linkedin-square"
                                                                                aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="feature feature-8"><img alt="Image" class='rounded-circle'
                                                            src="{{ asset('/img/team-chris-haveman.png') }}"
                                                            width="60%"/>
                            <h4>Drs. Chris Haveman</h4> <span>Board advisor & Business Strategy Operations</span>
                            <br><a href="https://www.linkedin.com/in/chrishaveman/"><i class="fa fa-linkedin-square"
                                                                                       aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="feature feature-8"><img alt="Image" class='rounded-circle'
                                                            src="{{ asset('/img/team-rob-van-dijk.png') }}"
                                                            width="60%"/>
                            <h4>Mr. Rob van Dijk</h4> <span>Laywer & Head of Legal Operations</span>
                            <br><a href="https://www.linkedin.com/in/rob-van-dijk-52a2296/"><i
                                        class="fa fa-linkedin-square"
                                        aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        + <b>11</b> skilled developers. NEXT have decided not to reveal the names of the developers in order to preserve safety.
                    </div>
                </div>
            </div>
        </section>

        <section class="bg--secondary timeline">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-md-offset-2 col-sm-10 col-sm-offset-1">
                        <div class="process-1">
                            <div class="process__item process__item_active">
                                <h4>November 2nd, 2017</h4>
                                <p> Pre-ICO launch</p>
                            </div>
                            <div class="process__item process__item_active">
                                <h4>November 20th, 2017</h4>
                                <p> Start Initial Coin Offering (ICO)</p>
                            </div>
                            <div class="process__item process__item_active">
                                <h4>December 10th, 2017</h4>
                                <p> First token integration into the exchange</p>
                            </div>

                            <div class="process__item process__item_active">
                                <h4>December 14th, 2017</h4>
                                <p> Showcase for <u>tokenholders</u></p>
                            </div>
                            <div class="process__item process__item_active">
                                <h4>December 31th, 2017</h4>
                                <p> End Initial Coin Offering (ICO)</p>
                            </div>
                            <div class="process__item process__item_active">
                                <h4>January 2018</h4>
                                <p> NEXT.exchange launch for tokenholders</p>
                            </div>
                            <div class="process__item process__item_active">
                                <h4>February-March 2018</h4>
                                <p> Start of acquire e-money license</p>
                            </div>
                            <div class="process__item">
                                <h4>Q2 2018</h4>
                                <p>Operational NEXT.exchange platform</p>
                            </div>
                            <div class="process__item">
                                <h4>Q3 2018</h4>
                                <p>Launch of NEXT.exchange <u>Investment Assets &amp; Funds</u></p>
                            </div>
                            <div class="process__item">
                                <h4>2019</h4>
                                <p>NEXT.exchange IPO on <u>EURONEXT</u> and <u>OTC</u> Market</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="text-center ptb40" style="background:#ececec">
            <div class="container">
                <div class="row">
                <div class="col-12">
                    <div class="switchable__text ptb40">
                        <h2>Ready to exchange on the NEXT exchange?</h2>
                        <blockquote class="f20">
                            &ldquo;We are building the most easy to use interface, so even your grandma can exchange in crypto&rdquo;
                        </blockquote>
                        <br>

                        <p >Get on board and sign up for <b>free</b>, no obligations, no pressure.
                            But, if you register, you will get access to our platform and you are ready to trade in the best cryptocoins and tokens.</p>
                        <br><br>
                        <a href="{{ url('/register') }}" class="btn-outline home-call-to-action"
                           style="color:#fff !important;font-weight:700; background:#282828; padding-top: -20px"
                           target="_blank">GET ONBOARD AND START TRADING</a>

                    </div>
                </div>

                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>

        <section id="channels" class="space--xxs" style="background:#ececec">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <div class="footer-channels">

                        <span><a href="https://nextexchange.slack.com" target="_blank"><i
                                        class="fa fa-slack fa-2x"></i></a></span> <span><a
                                        href="https://t.me/next_exchange" target="_blank"><i
                                            class="fa fa-telegram fa-2x"></i></a></span> <span><a
                                        href="https://www.facebook.com/nextexchanger/" target="_blank"><i
                                            class="fa fa-facebook fa-2x"></i></a></span> <span><a
                                        href="https://twitter.com/NextExchange" target="_blank"><i
                                            class="fa fa-twitter fa-2x"></i></a></span>
                            <span><a href="https://medium.com/nextexchange" target="_blank"><i
                                            class="fa fa-medium fa-2x"></i></a></span><span><a
                                        href="#"><i
                                            class="fa fa-btc fa-2x"></i></a></span>
                            <span><a href="https://github.com/NextExchange"><i
                                            class="fa fa-github fa-2x"></i></a></span>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop
