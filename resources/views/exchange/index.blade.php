@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')
    @include('_partials.flashalert')
    <?php
    define('REFRESH_SECONDS', 30);
    ?>

    <section class="section-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-7"><p class="section-header__title">Tokenmarket</p>
                    <p class="section-header__desc">Welcome to the NEXT tokenmarket. Here you can trade or buy your
                        ERC20
                        tokens instantly and directly from other holders. All you need to do is install MetaMask.</p>
                </div>
                <div class="col-12 col-sm-5 text-right"><a class="section-header__btn" href="https://metamask.io"
                                                           style="min-width: 205px"
                                                           target="_blank">Download MetaMask
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g fill="none" fill-rule="evenodd">
                                <path fill="none" d="M0 0h16v16H0z"></path>
                                <path stroke="#FFF" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.33"
                                      d="M8 4l4 4-4 4 4-4H4h4"></path>
                            </g>
                        </svg>
                    </a></div>
            </div>
        </div>
    </section>

    <section style="background-color: #00AEFF">
        <div class="col-12 ">

            <div class="next-table__body spinner-wrapper">
                <div class="next-table__body-head">
                    <div class="col-lg-1 col-4 coins__sort"><span>Currency</span></div>
                    <div class="col-lg-3 coins__sort text-right hidden-mobile"><span>Address</span></div>
                    <div class="col-lg-3 col-4 coins__sort text-right"><span>Price</span></div>
                    <div class="col-lg-1 coins__sort hidden-mobile text-right"><span>Change</span></div>
                    <div class="col-lg-2 text-right coins__sort hidden-mobile"><span>Volume 24h (USD)</span></div>
                    <div class="col-lg-2 col-4 text-right"><span>Actions</span></div>
                </div>

                <div style="position: relative; overflow: hidden; width: 100%; height: 400px;">
                    <div style="position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; overflow: auto; margin-right: 0px; margin-bottom: 0px;">
                        <ul class="next-table__list">
                        </ul>
                    </div>
                    <div class="track-horizontal" style="position: absolute; height: 6px; display: none;">
                        <div style="position: relative; display: block; height: 100%; cursor: pointer; border-top-left-radius: inherit; border-top-right-radius: inherit; border-bottom-right-radius: inherit; border-bottom-left-radius: inherit; background-color: rgba(0, 0, 0, 0.2);"></div>
                    </div>
                    <div class="track-vertical" style="position: absolute; width: 6px; display: none;">
                        <div style="position: relative; display: block; width: 100%; cursor: pointer; border-top-left-radius: inherit; border-top-right-radius: inherit; border-bottom-right-radius: inherit; border-bottom-left-radius: inherit; background-color: rgba(0, 0, 0, 0.2);"></div>
                    </div>
                </div>
                <div class="spinner-block" style="display: none;">
                    <div class="loading"></div>
                    <div class="spinner-bg"></div>
                </div>
            </div>
            <div class="next-table__mobile-btns">
                <button>Next page
                    <svg width="16" height="16" viewBox="0 0 16 16">
                        <path fill="#828EA1" fill-rule="nonzero"
                              d="M10.395 7.335H12c.175 0 .325.063.437.162l.033.033a.664.664 0 0 1 0 .94l-4 4-.94-.94 4-4L12 8.665H4v-1.33h4a.665.665 0 1 1 0 1.33H4c-.887 0-.887-1.33 0-1.33h6.395L7.53 4.47a.665.665 0 0 1 .94-.94l3.967 3.967a.68.68 0 0 1 .226.57.681.681 0 0 1-.193.403l-4 4c-.627.627-1.567-.313-.94-.94l4-4v.94l-1.135-1.135z"></path>
                    </svg>
                </button>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('scripts_footer')
    <script type="text/javascript">
        window.module = {};
    </script>
    <script type="text/javascript" src="{{env('LARAVEL_ECHO')}}/socket.io/socket.io.js"></script>
    <script type="text/javascript" src="{{asset('js/echo.js')}}"></script>
    <script type="text/javascript">
        var isUndefined = function (object) {
            return object === undefined;
        };

        var objectDot = function (path, object, defaultValue = '') {
            var segments = path.split('.');
            var newObject = object[segments.splice(0, 1)];
            if (newObject === null) return defaultValue;
            if (isUndefined(newObject)) return object;
            return objectDot(segments.join('.'), newObject)
        };

        var findAndReplace = function (template, data) {
            return template.replace(/\{%((\w+\.?){0,})%\}/g, function (match, name) {
                var value = objectDot(name, data);
                return value;
            });
        };

        var renderRow = function (coin, template) {
            return $('<li />').html(
                findAndReplace(template, coin)
            );
        };

        var handleResponse = function (data) {
            data = data.data;
            var template = $('#coin-row').html();

            for (var coin of data) {
                $('.next-table__list').append(
                    renderRow(coin, template)
                );
            }
            setTimeout(function () {
                $('.spinner-block').hide();
            }, 200);
        };

        var updateTokenMarket = function () {
            $('.spinner-block').show();
            $.get('/api/token-market', handleResponse);
        };

        updateTokenMarket();

        if (window.io) {
            var token = document.head.querySelector('meta[name="csrf-token"]');
            Echo = new Echo({
                broadcaster: 'socket.io',
                host: location.origin + ':3000',
                reconnectionAttempts: 5,
                csrfToken: token.content
            });

            Echo.channel('currency')
                .listen('.crypto-cap-updated', updateTokenMarket);
        }

    </script>
    <script id="coin-row" type="text/html">
        <div class="col-4 col-lg-1">
            <img class="icon"
                 src="/img/coin/64/{%name%}.png"
                 onerror="this.src='/img/coin/64/noimage.png'"
                 alt="{%name%}">
            <div class="next-table__list-title">{%title%}
                <div class="show-mobile {%class}}">{%change%}%</div>
            </div>
        </div>
        <div class="col-2 col-lg-3 hidden-mobile text-right"
             style="overflow:auto">{%address%}
        </div>
        <div class="col-4 col-lg-3 text-right">
            <span class="eur-price">{%prices.usd%}$</span>
            <span class="price__wrapper">{%prices.btc%}</span>
        </div>

        <div class="col-2 col-lg-1 hidden-mobile text-right">

            <span class="{%class}}">{%change%}%</span>
        </div>

        <div class="col-2 col-lg-2 vol hidden-mobile">{%volume%}$</div>

        <div class="col-4 col-lg-2 text-right"><a
                    class="btn btn--outline btn-primary "
                    href="/tokenmarket/{%name%}">Enter {%name%}
                exchange</a>
        </div>
    </script>
@endsection
