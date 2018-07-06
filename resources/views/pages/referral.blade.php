@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    <section id="referral">
        <div class="container ">
            <div class="row">


                        <div class="col-12 masonry__item">
                            <div class="card card-2 text-center">
                                <div class="card__top">
                                    <a href="#"> <img alt="refer_a_friend" src="http://www.ahrsunrooms.com/wp-content/uploads/2017/04/refer.png"> </a>
                                </div>
                                <div class="card__body">
                                    <h4>Refer a Friend to the tokensale</h4> <span class="type--fade">And get rewarded with NEXT tokens</span>
                                    <p> We would be glad if you refer NEXT to other crypto enthusiasts who could be interested to buy NEXT Tokens.&nbsp;<br><br>For each token your friend buy, you will be rewarded with 1% NEXT tokens that he/she buys.<br><br>Please <a href="/login">LOGIN</a> to view your personal referral link.</p>
                                </div>
                                <div class="card__bottom text-center">
                                    <div class="card__action"> <span class="h6 type--uppercase"><p>LOGIN</p></span>
                                        <a href="/login"> <i class="fa fa-sign-in" aria-hidden="true"></i> </a>
                                    </div>
                                    <div class="card__action"> <span class="h6 type--uppercase"><p>REGISTER</p></span>
                                        <a href="/register"> <i class="fa fa-plus" aria-hidden="true"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>


            </div>
        </div>
    </section>




@stop