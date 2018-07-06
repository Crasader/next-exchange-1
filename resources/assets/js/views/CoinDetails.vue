<template>
    <div class="animated fadeIn container-fluid myContainerOuter">
        <section class="currencies-body">
            <div class="container myContainer">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-3 pull-left">
                        <div class="row flex-nowrap flex-row align-items-center">
                            <h2><img v-bind:src="'/img/coin/32/'+content.coin_coin+'.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'"> {{content.coin_title}} </h2>
                            <span class="coin-short-name ml-3 text-uppercase">{{content.coin_coin}}</span>
                        </div>
                    </div>
                    <div class="col-md-4 pull-right">
                        <div class="row justify-content-between">
                            <div class="pull-right row">
                                <div class="price-details-box text-right">
                                    <h3><sup class="currency">$</sup>{{ coinDetails.price }}</h3>
                                    <!--\.<span class="btc-price">0.06606920 <span class="text-uppercase">btc</span></span>-->
                                </div>
                                <div class="price-stats ml-2 mr-2">
                                    <span v-if="parseFloat(coinDetails.change) == 0"><i class="fa"
                                                                                        aria-hidden="true"></i>&nbsp;{{coinDetails.change}} %</span>
                                    <span class="text-success" v-else-if="parseFloat(coinDetails.change) > 0.0000"><i
                                            class="fa fa-arrow-up" aria-hidden="true"></i>&nbsp;{{coinDetails.change}} %</span>
                                    <span class="text-danger" v-else><i class="fa fa-arrow-down" aria-hidden="true"></i>&nbsp;{{coinDetails.change}} %</span>
                                    <!--/.<span class="m-2">{{ coinDetails.change }}%</span>-->
                                    <!--/.<span class="m-2">-2.15%</span>-->
                                </div>
                            </div>
                            <router-link :to="'/orderbook/' + content.coin_coin + '/BTC'" v-if="content.symbol === 'ETH'"> <button class="btn btn-primary btn-buy-instantly mt-auto mb-auto align-self-end ml-0 rounded"> Buy/Sell Instantly </button> </router-link>
                            <router-link :to="'/orderbook/' + content.coin_coin + '/ETH'" v-else> <button class="btn btn-primary btn-buy-instantly mt-auto mb-auto align-self-end ml-0 rounded"> Buy/Sell Instantly </button> </router-link>

                        </div>
                    </div>
                </div>
                <div class="gap-10"></div>
                <div class="row border border-secondary">
                    <div class="col-md-3 p-0">
                        <div class="border-bottom market-cap-details border-right">
                            <ul class="p-4">
                                <li class="mb-4">
                                    <small class="text-uppercase mb-3">Market Cap:</small>
                                    <h4 class="m-0">${{ coinDetails.marketCap }}</h4>
                                    <!--/.<small>6,465,852 <span class="text-uppercase">btc</span></small>-->
                                </li>
                                <li class="mb-4">
                                    <small class="text-uppercase mb-3">Volume (24H)</small>
                                    <h4 class="m-0">${{ coinDetails.volume }}</h4>
                                    <!--/.<small>490,833 <span class="text-uppercase">btc</span></small>-->
                                </li>
                                <li class="mb-4">
                                    <small class="text-uppercase mb-3">Circulating supply</small>
                                    <h4 class="m-0">${{ coinDetails.supply }} <span class="text-uppercase"></span>{{content.coin_coin}}
                                    </h4>
                                </li>
                            </ul>
                        </div>
                        <div class="market-cap-links border-right">
                            <ul class="p-4">
                                <li class="mb-4 tags">
                                    <ul>
                                        <li><i class="fa fa-tag mr-2"></i><span
                                                class="badge badge-light p-1">Mineable</span></li>
                                    </ul>
                                </li>
                                <li class="mb-3 weblinks">
                                    <a href="#" title="Website"><i class="fa fa-link mr-2"></i>Website</a>
                                </li>
                                <li class="mb-3 weblinks">
                                    <a href="#" title="Website"><i class="fa fa-search mr-2"></i>Explorer 1</a>
                                </li>
                                <li class="mb-3 weblinks">
                                    <a href="#" title="Website"><i class="fa fa-search mr-2"></i>Explorer 2</a>
                                </li>
                                <li class="mb-3 weblinks">
                                    <a href="#" title="Website"><i class="fa fa-search mr-2"></i>Explorer 3</a>
                                </li>
                                <li class="mb-3 weblinks">
                                    <a href="#" title="Website"><i class="fa fa-list mr-2"></i>Messageboard</a>
                                </li>
                                <li class="mb-3 weblinks">
                                    <a href="#" title="Website"><i class="fa fa-bullhorn mr-2"></i>Announcement</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-lg-12 p-4">
                                <div class="card high-charts">
                                    <div class="spinner-wrapper">
                                        <div class="card-block">
                                            <div class="chart-wrapper" style="height:405px;margin-top:20px;">
                                                <div id="coin-chart">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <strong>${{ chartTotal }}</strong>
                                            <span class="float-right"><i class="fa" v-bind:class="chartUpDown.sign"></i> {{ chartUpDown.updown }}</span>
                                        </div>
                                        <!--/.<div id="loader-div" class=""></div>
                                        <div id="spinner" class=""></div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="loader-div" class=""></div>
                <div id="spinner" class=""></div>

            </div>
        </section>

        <notifications group="notify-user" position="bottom right"/>
        <audio ref="audioElm" src="../sounds/transaction-notify.mp3"></audio>
    </div>

</template>

<style>
    .myContainerOuter, .myContainer {
        padding: 0px !important;
    }

    :root{
        --secondary: #b8c1ca;
        --orange: #ffa265;
        --light-orange: #fdece4;
    }

    * {
        font-family: 'Open Sans Regular', 'Open Sans', 'Helvetica Neue', Helvetica, Arial, "PingFang SC", "Microsoft Yahei", sans-serif;;
    }

    section.currencies-body {
        margin-top: 30px;
        background: #fff;
        padding: 30px;
    }

    .card.high-charts {
        box-shadow: none
    }

    .coin-short-name {
        color: var(--blue);
    }

    .gap-10 {
        height: 10px
    }

    .price-stats span {
        display: block;
    }

    .market-cap-details ul li span, .market-cap-details ul li small {
        color: var(--secondary)
    }

    .market-cap-links ul li .badge {
        color: var(--orange);
        background-color: var(--light-orange);
        border-color: var(--light-orange);
    }

    .market-cap-links ul li.weblinks .fa,
    .market-cap-links ul li.tags .fa {
        color: var(--secondary);
    }

    .market-cap-links ul li.weblinks a {
        color: #000
    }

    .price-details-box h3 sup.currency {
        font-size: 14px;
        top: -8px;
        margin-right: 5px;
    }
</style>
<script>

    let highChart   = require('highcharts/highstock');
    require('highcharts/modules/exporting')(highChart);
    require('highcharts/highcharts-more')(highChart);
    require('highcharts/indicators/indicators')(highChart);
    require('highcharts/indicators/volume-by-price')(highChart);

    let numeral     = require('numeral');
    let moment      = require('moment');
    let math        = require('mathjs');

    export default {

        name: 'CoinDetails',
        data() {
            return {
                user_id: 0,
                chartRequest: null,
                coins: [{}],
                chartInit: true,
                chartData: [],
                chartTotal: 0,
                chartUpDown: {},
                content: [],
                coinId: '',
                coinDetails: [],
                updateInterval: 60 * 1000
            }
        },

        created: function () {
            this.set_current_user();
            this.getCoinId();
            this.interval = setInterval(this.getCoinDetails, this.updateInterval);
        },

        mounted: function () {
            //initial loading of spinners
            $('#spinner').addClass('spinner-bg');
            $('#loader-div').addClass('loading');
        },

        watch: {},

        methods: {

            set_current_user: function () {

                $.ajax({
                    type: "GET",
                    url: "/set-user",
                    cache: false,
                    success: function (response, status, error) {
                        this.user_id = response.user_id;
                    }.bind(this),

                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            chartDisplay: function () {

                let self = this;

                let post_data = {
                    symbol: this.$route.params.market,
                    coin_id: this.coinId
                };

                self.chartRequest = $.ajax({
                    type: "GET",
                    url: '/chartdata',
                    data: post_data,
                    cache: false,
                    beforeSend: function () {

                        if (self.chartRequest != null) {

                            //Aborting the same previous request if exists
                            self.chartRequest.abort();
                            self.chartRequest = null;
                        }
                    },
                    success: function (response, status, error) {

                        Vue.set(this, 'chartData', response.data);
                        Vue.set(this, 'chartTotal', response.total);

                        let clsSign = response.updown < 0 ? 'fa-arrow-circle-o-down text-danger' : 'fa-arrow-circle-o-up text-success';
                        clsSign = isNaN(response.updown) || (response.updown == 0) ? '' : clsSign;

                        this.chartUpDown = {

                            updown: isNaN(response.updown) ? '' : Math.abs(numeral(response.updown).format('0.00')) + '%',
                            sign: clsSign
                        };

                        let groupingUnits = [
                            [
                                'day',
                                [1, 7]
                            ],
                            [
                                'week',                         // unit name
                                [1]                             // allowed multiples
                            ],
                            [
                                'month',
                                [1, 2, 3, 4, 6]
                            ]
                        ];

                        let self = this;
                        // create the chart
                        let chart = highChart.stockChart('coin-chart', {

                            rangeSelector: {
                                selected: 1
                            },

                            yAxis: [{
                                startOnTick: false,
                                endOnTick: false,
                                labels: {
                                    align: 'right',
                                    x: -3
                                },
                                title: {
                                    text: 'UP & DOWN'
                                },
                                height: '60%',
                                lineWidth: 2,
                                resize: {
                                    enabled: true
                                }
                            }, {
                                labels: {
                                    align: 'right',
                                    x: -3
                                },
                                title: {
                                    text: 'Volume'
                                },
                                top: '65%',
                                height: '35%',
                                offset: 0,
                                lineWidth: 2
                            }],

                            tooltip: {
                                split: false
                            },

                            plotOptions: {
                                series: {
                                    dataGrouping: {
                                        units: groupingUnits
                                    }
                                },
                                candlestick: {
                                    color: '#F15C70',
                                    upColor: '#0AE39E'
                                }
                            },
                            exporting: {enabled: false},

                            series: [{
                                type: 'line',
                                name: post_data.symbol,
                                id: 'aapl',
                                zIndex: 2,
                                data: response.graph
                            }, {
                                type: 'column',
                                name: 'Volume',
                                id: 'volume',
                                data: response.volume,
                                yAxis: 1
                            }],
                            xAxis: {
                                events: {
                                    setExtremes: function (event) {

                                        post_data.start_date = numeral(event.min).divide(1000).value();
                                        post_data.end_date = numeral(event.max).divide(1000).value();

                                        if (event.hasOwnProperty('DOMEvent') && event.DOMEvent.type !== 'mouseup') {
                                            return false;
                                        } //continue only in mouse release

                                        $.ajax({
                                            type: "POST",
                                            url: '/chartzoom',
                                            data: post_data,
                                            cache: false,
                                            success: function (response, status, error) {

                                                self.chartTotal = response.total;

                                                let clsSign = response.updown < 0 ? 'fa-arrow-circle-o-down text-danger' : 'fa-arrow-circle-o-up text-success';
                                                clsSign = isNaN(response.updown) || (response.updown == 0) ? '' : clsSign;

                                                self.chartUpDown = {
                                                    updown: isNaN(response.updown) ? '' : Math.abs(numeral(response.updown).format('0.00')) + '%',
                                                    sign: clsSign
                                                };
                                            }.bind(this),
                                            error: function (data, status, error) {
                                                console.log(error);
                                            }
                                        });
                                    }
                                }
                            }
                        });

                        $('.highcharts-credits').hide();

                    }.bind(this),
                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },
            getCoinId() {

                let self = this;
                let data = {
                    coin_coin: this.$route.params.market
                };
                $.ajax({
                    type: "GET",
                    url: '/coin-id',
                    data: data,
                    cache: false,
                    success: function (response, status, error) {
                        self.coinId = response.coin_id;
                        this.chartDisplay();
                        this.Content();
                    }.bind(this),
                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },
            Content() {

                let data	= {
                    coin_id: this.coinId
                };
                $.ajax({
                    type: "GET",
                    url: '/getCoindata/'+data.coin_id ,
                    cache: false,
                    success: function(response, status, error) {
                        this.content = response.data;
                        this.getCoinDetails();
                    }.bind(this),
                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },
            getCoinDetails() {
                let data	= {
                    coin_coin: this.$route.params.coin,
                    coin: this.$route.params.market
                };
                $.ajax({
                    type: "GET",
                    url: '/getPerCoinDetails',
                    data: data,
                    cache: false,
                    success: function(response, status, error) {
                        this.coinDetails = response.data;
                        $('#loader-div').removeClass('loading');
                        $('#spinner').removeClass('spinner-bg');
                    }.bind(this),
                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },
            formatValidatorErrors: function (errors) {
                let formatted = [];
                for (let i in errors) {
                    formatted[i] = errors[i][0];
                }
                return formatted;
            }
        },

    }
</script>