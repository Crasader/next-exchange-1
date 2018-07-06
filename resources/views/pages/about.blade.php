@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')

    <div class="banner bg-blue">
        <span data-lang-id="about_pageTitle">About us</span>
    </div>
    <section class="currencies-body">
        <div class="container">
            <h4 data-lang-id="about_pageSubtitle">About us</h4>
            <p data-lang-id="about_description">About us</p>
        </div>
    </section>

    <section id="apply">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 offset-lg-2 col-md-12 offset-md-1 text-center mt-4 preamble">
                    <h3 class="title mt-0 mb-3">Apply your ICO</h3>

                    <p class="lead mb-5"> <span>Businesses will be given the exclusive opportunity to launch within our new exchange.</span><br><br></p>
                </div>


            </div>
            <style>
                @CHARSET "UTF-8";

                ul.trading__info {
                    position: relative;
                }

                .tradable__block {
                    display: inline-block;
                    width: 160px;
                    height: 160px;
                    background-color: rgba(0,0,0,0.05);
                    border: 1px solid rgba(0,0,0,0.1);
                }

                li.tradable__block {
                    margin-right: 10px;
                    margin-bottom: 10px;
                }

                .tradable__block.pinned {
                    position: absolute;
                    right: 0px;
                    top: 0px;
                }

                .tradable__block > div {
                    position: relative;
                    height: calc(100% - 20px);
                    margin: 10px;
                }

                .tradable__block > div > span.company {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    width: 100%;
                    height: auto;
                    line-height: 20px;
                    font-size: 18px;
                    font-family:'SourceSansPro-Semibold';
                    color: #3980A8;
                }

                .tradable__block > div > span.ticker {
                    position: absolute;
                    top: 60px;
                    left: 0px;
                    font-size: 24px;
                    color: #555;
                }

                .tradable__block > div > span.ticker > span.up {
                    color: #60BB22;
                }

                .tradable__block > div > span.ticker > span.down {
                    color: #DD1316;
                }

                .tradable__block > div > span.volume {
                    position: absolute;
                    top: 90px;
                    left: 0px;
                    font-size: 16px;
                    color: #555;
                }

                .tradable__block > div > span.date__updated {
                    position: absolute;
                    top: 110px;
                    left: 0px;
                    font-size: 12px;
                    color: #ccc;
                }

                .tradable__block.unset span {
                    color: rgba(0,0,0,0.2) !important;
                }





                span.percent:before {
                    content: "%";
                }

                .price__change > span.up {
                    color: #080;
                }

                .price__change > span.down {
                    color: #800;
                }

                .price__change span.amount {

                }

                .price > span.currency {
                    color: #999;
                }

                .toolbar > .collection > li > h2 {
                    margin: 0px;
                }

                .collection {

                }

                .collection > li,
                .collection > span {
                    margin-right: 5px;
                    display: inline-block;
                }

                .collection > li:last-child,
                .collection > span:last-child {
                    margin-right: 0px;
                }

                .grid span > img.small.region {
                    position: absolute;
                    top: 1px;
                    right: 5px;
                    width: 18px;
                    height: 18px;
                }


                /*span.ticker {*/
                /*	padding: 4px 10px;*/
                /*	background-color: #FFA500;*/
                /*	border-radius:10px; -moz-border-radius:10px; -webkit-border-radius:10px;*/
                /*	margin-right: 10px;*/
                /*}*/

                #ico__listings {
                    position: relative;
                    display: block;
                    height: auto;
                    width: 100%;
                }

                #ico__listings > li {
                    position: relative;
                    display: inline-block;
                    width: 500px;
                    border: 1px solid transparent;
                    margin-bottom: 10px;
                    margin-right: 10px;
                    height: 300px;
                    background-color: #fff;
                }

                #ico__listings > li:hover {
                    border: 1px solid #ddd;
                }

                #ico__listings > li > span.display {
                    position: absolute;
                    text-align: right;
                    font-family: 'SourceSansPro-Light';
                    font-size: 20px;
                    bottom: 10px;
                    right: 10px;
                    background-color: #fff;
                }

                #ico__listings > li > div.overlay {
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    display: none;
                    width: 150px;
                    height: 100%;
                    background-color: rgba(255,255,255,0.9);
                    border-right: 1px solid #ddd;
                }

                #ico__listings > li:hover > div.overlay {
                    display: block;
                }

                .can__stockquote {
                    position: relative;
                    background-color: transparent;
                    image-rendering: optimizeSpeed; // Older versions of FF
                image-rendering: -moz-crisp-edges; // FF 6.0+
                image-rendering: -webkit-optimize-contrast; // Webkit // (Safari now, Chrome soon)
                image-rendering: -o-crisp-edges; // OS X & Windows Opera (12.02+)
                image-rendering: optimize-contrast; // Possible future browsers.
                -ms-interpolation-mode: nearest-neighbor; // IE
                }

                .ico__info {
                    position: relative;
                }

                .ico__info > li {
                    position: relative;
                    line-height: 40px;
                }

                .ico__info > li.header {

                }

                .ico__info > li.header > h3 {
                    margin-bottom: 5px;
                    margin-top: 30px;
                }

                .ico__info > li.header:first-child > h3 {
                    margin-top: 0px;
                }

                .ico__info > li:not(.header) {
                    padding: 10px 10px;
                    margin-left: -10px;
                }

                .ico__info > li ~ li:not(.header) {
                    /*	border-top: 1px solid #eee;*/
                }

                .ico__info > li:last-child {
                    border-bottom: none;
                }

                .ico__info > li > ul > li {
                    position: relative;
                    display: block;
                    width: 100%;
                    height: auto;
                    line-height: 40px;
                    padding: 10px 10px 10px;
                    margin-left: -10px;
                    border-top: 1px solid #eee;
                }

                .ico__info > li > ul > li:after {
                    content: "."; display:block; clear:both; height:0; visibility:hidden;
                }

                .ico__info > li:after {
                    content: "."; display:block; clear:both; height:0; visibility:hidden;
                }

                .ico__info > li span.display {
                    font-family: 'SourceSansPro-Light';
                    font-size: 20px;
                    margin-right: 10px;
                    top: -8px;
                    z-index: 10;
                }

                .ico__info > li span.display > span.new {
                    position: relative;
                    top: -10px;
                    left: 5px;
                    background-color: #5E8938;
                    color: #fff;
                    padding: 2px 4px;
                    font-family: 'SourceSansPro-Light';
                    font-size: 10px;
                    line-height: 10px;
                    border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;
                }

                .ico__info > li span.volume {
                    position: absolute;
                    left: 14px;
                    top: 30px;
                    color: #888;
                    font-family: 'SourceSansPro-Light';
                    font-size: 10px;
                    margin-right: 10px;
                }

                .ico__info > li > img {
                    width: 16px;
                    height: 16px;
                    position: absolute;
                    left: -14px;
                    top: 12px;
                }

                span.current__price {
                    font-family: 'SourceSansPro-ExtraLight';
                    font-size: 20px;
                    margin-right: 10px;
                }

                span.current__price > span.currency {
                    color: #999;
                    width: 40px;
                    margin-left: 5px;
                    font-size: 0.6em;
                    top: -5px;
                }

                #ico__listings > li > span.current__price {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    font-size: 30px;
                    margin-right: 0px;
                }

                .ico__info > li span.container {
                    position: relative;
                    height: auto;
                }

                .ico__info > li span.button {
                    position: relative;
                    top: -2px;
                }

                .ico__info > li:hover {
                    position: relative;
                }



            </style>
            <div class="row">
                <div class="col-lg-12">

                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated">1:23 PM EST</span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated">1:23 PM EST</span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated">1:23 PM EST</span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated">1:23 PM EST</span>
                        </div></li>
                    <li class="tradable__block unset">
                        <div class="inner__ident">
                            <span class="company">Your Company</span>
                            <span class="ticker"><span class="">▶︎ 0.00 USD</span></span>
                            <span class="volume">5,000,000</span>
                            <span class="date__updated">1:23 PM EST</span>
                        </div></li>



                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <a class="flat-butt" data-event="{'action':'WorkFlow','event':'showSignup','args':{}}">Apply</a>
                </div>
            </div>

            <br><br>

            <div class="row">
                <div class="col-lg-12">
                    <small>


                        <h3>Conditions</h3>

                        <ul class="conditions">



                            <li><span>For elligability your business must:</span></li>

                            <li><span class="indent">* be incorporated as a legal entity</span></li>

                            <li><span class="indent">* have not launched / will not launch a similar or identical sale (directly or indirectly)</span></li>

                            <li><span class="indent">* not have received (through investment or revenue) a sum of more than 1,000,000 (one million euros) since the date of incorporation</span></li>

                            <li><span class="indent">* have a White-Paper detailing the use of the token/coin and have a clear definition of the services provided</span></li>

                            <li><span class="indent">* your business intends to launch its initial coin offering right now</span></li>

                            <li><span>All applications will undergo a detailed check regarding the intentions, whitepaper and proposed funding methods. Our team will also undertake background checks to ensure compliance with regulatory processes and requirements. In addition all applicants will be required to assist in such processes as KYC (Know Your Customer) and AML (Anti-Money Laundering) throughout the ICO lifetime.</span></li>

                        </ul>


                    </small>
                </div>
            </div>
        </div>
    </section>


@stop