<template>
    <div class="animated fadeIn container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header" style="display: flex;justify-content: space-between;">
                        <div style="display: inherit">
                            <i class="icon-chart text-muted mr-1" style="margin-top: 0.7em;"></i>
                            <div style="padding: 0 .15em;">
                                <v-select v-if="coins.length > 0" v-model="selected_coin" label="symbol"
                                          :options="coins">
                                    <template slot="selected-option" slot-scope="coin">
                                        <img class="coin-symbol" :src="'/img/coin/16/'+ coin.symbol +'.png'">
                                        {{ coin.symbol }}
                                    </template>
                                    You want another text?
                                    <template slot="option" slot-scope="coin">
                                        <img class="coin-symbol" :src="'/img/coin/16/'+ coin.symbol +'.png'">
                                        {{ coin.symbol }}
                                    </template>
                                </v-select>
                            </div>
                        </div>
                        <span style="float: right;">Current price <strong>${{usdPricePerCoin}}</strong></span>
                    </div>
                    <div class="spinner-wrapper">
                        <div class="card-block">
                            <div class="chart-wrapper" style="height:405px;margin-top:20px;">
                                <div id="coin-chart">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <strong>${{ chartTotal }}</strong>
                            <span class="float-right">
                                <i class="fa" v-bind:class = "chartUpDown.sign"></i> {{ chartUpDown.updown }}
                            </span>
                        </div>
                        <div id="loader-div" class=""></div>
                        <div id="spinner" class=""></div>
                    </div>
                </div>
                <!--/.card-->
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header sectionTitle"  >
                        <B >Orderbook</B>
                        <span>{{market_title}}/{{transaction_coin.coin_coin}}</span>
                    </div>
                    <div class="spinner-wrapper">
                        <div class="card-block">
                            <div v-if="sellListSorted.length <= 0 && buyListSorted.length <= 0" class="emptyBox">
                                <span>
                                    No orders
                                </span>
                            </div>
                            <div v-else  class="orderbookOuter" style="max-height:405px; margin-top:20px; overflow-x: hidden; overflow-y: auto">
                                
                                <div class="row" style="z-index: 2">
                                    <div class="col-6 orderbookHead" style="border-right: 1px solid #e2dff5;">

                                        <div class="row">
                                            <div class="col-4">Buying {{market_title}}</div>
                                            <div class="col-4"></div>
                                            <div class="col-4">
                                                <span>Total:</span>
                                                <span>{{ totalBuyAmount }} {{market_title}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 orderbookHead">

                                         <div class="row">
                                              <div class="col-4">Selling {{market_title}}</div>
                                            <div class="col-4"></div>
                                            <div class="col-4">
                                                <span>Total:</span>
                                                <span>{{ totalSellAmount }} {{market_title}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div  class="row" style="z-index: 3">
                                    <div class="col-6 orderbookTableHead" style="border-right: 1px solid #e2dff5;">
                                        <div class="row">
                                            <div class="col-4">Bid {{transaction_coin.coin_coin}}</div>
                                            <div class="col-4">Amount {{market_title}}</div>
                                            <div class="col-4">Total {{transaction_coin.coin_coin}}</div>
                                        </div>
                                    </div>
                                    <div  class="col-6 orderbookTableHead">
                                        <div class="row">
                                            <div class="col-4">Ask {{transaction_coin.coin_coin}}</div>
                                            <div class="col-4">Amount {{market_title}}</div>
                                            <div class="col-4">Sum {{transaction_coin.coin_coin}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="z-index: 4">
                                    <div class="col-6 orderbookHalf orderbookContent" style="border-right: 1px solid #e2dff5;">
                                        <div class="row" id="rpane-buy" v-for = "(itemBuy, index) in buyListSorted" style="position:relative" :key="index"
                                             v-bind:class="itemBuy.order_user_id == user_id ? 'font-weight-bold' : 'font-weight-normal'">
                                            <div class="orderbookContentBar orderbookContentBar-buy" :style="{width: `${ relativeWidth( buyListSortedBidsArray, index) }%` }" ></div>
                                            <div class="col-4">{{ itemBuy.order_price|format_decimals }}</div>
                                            <div class="col-4">{{ itemBuy.order_amount }}</div>
                                            <div class="col-4">{{ itemBuy.order_total|format_decimals }}</div>
                                            <div class="orderbookBtnContainer">
                                                <button @click="takeOrder(itemBuy)" title="Take this order" class="orderbookBtn">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 orderbookHalf orderbookContent" >
                                        <div class="row" id="rpane-sell" v-for = "(itemSell, index) in sellListSorted" style="position:relative" :key="index"
                                             v-bind:class="itemSell.order_user_id == user_id ? 'font-weight-bold' : 'font-weight-normal'">
                                            <div class="orderbookContentBar orderbookContentBar-sell" :style="{width: `${ relativeWidth( sellListSortedBidsArray, index) }%` }"></div>
                                            <div class="col-4">{{ itemSell.order_price|format_decimals }}</div>
                                            <div class="col-4">{{ itemSell.order_amount }}</div>
                                            <div class="col-4">{{ itemSell.order_total|format_decimals }}</div>
                                            <div class="orderbookBtnContainer">
                                                <button @click="takeOrder(itemSell)" title="Take this order" class="orderbookBtn">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card-footer" style="z-index: 4">
                            <strong>{{ totalTrades }} trades</strong>
                            <span class="float-right">
                                <i class="fa " v-bind:class = "percentageUpDown.sign"></i> {{ percentageUpDown.updown }}
                            </span>
                        </div> -->
                        <div class="orderbook-exchange-loader" ></div>
                        <div class="orderbook-exchange-spinner" ></div>
                    </div>
                </div>
            </div>

            <!--
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i class="icon-chart text-muted mr-1"></i><B>Order History</B>
                    </div>
                    <div class="spinner-wrapper">
                        <div class="card-block">
                            <div v-for="(order_histories, index) in order_history">
                                <div class="row p-3 mb-2 bg-light" v-if="index===0">
                                    <div class="col-sm">
                                        <strong>{{ order_histories.order_maincoin }} / {{ order_histories.order_market
                                            }} </strong>
                                    </div>
                                    <div class="col-sm">
                                        <strong>{{ order_histories.order_market }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        <strong>{{ order_histories.order_maincoin }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        Date
                                    </div>
                                </div>

                                <div class="row p-3 mb-2"
                                     v-bind:class="{'text-success': order_histories.order_buysell == 1, 'text-danger': order_histories.order_buysell == 2}">
                                    <div class="col-sm">
                                        {{ order_histories.order_price | format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ order_histories.order_amount | format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ order_histories.order_total | format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ order_histories.update_at | format_date }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="loader-div-transactions" class=""></div>
                        <div id="spinner-transactions" class=""></div>
                    </div>
                </div>
            </div>
            -->
        </div>
        <!--/.col-->
        <div class="row">
            <div class="col-lg-6">
                <div class="spinner-wrapper" id="buy-or-sell">
                    <div class="card">
                        <div class="card-header">
                            <div class="box-left">Buy</div>
                        </div>
                        <transition name="fade" >
                            <div class="alert alert-success" role="alert" style="text-align: center; z-index: 1000" v-if="order_success_BUY">
                                <strong>Your order for buying {{ selected_coin.symbol }} {{ order_amount_BUY }}@{{order_price_BUY}} is placed.</strong>
                            </div>
                        </transition>
                        <transition name="fade" >
                            <div v-bind:class="fee_msg_class" role="alert" style="text-align: center; z-index: 1000" v-if="fee_msg_BUY">
                                <strong v-html="fee_msg_BUY"></strong>
                            </div>
                        </transition>
                        <div class="card-block ptb40 order-form">
                            <div class="form-group row input-row">
                                <label for="inputAmountBuy" class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-sm-10">
                                    <div class="input-group coin-amount-input">
                                        <input @keypress="handleNumericalInput"
                                               class="form-control form-control decimal-input" type="text"
                                               id="inputAmountBuy"
                                               min="0.000000001" placeholder="Amount" v-model="order_amount_BUY">
                                        <div class="input-group-btn input-group-append coins-selection">
                                            <button type="button" class="btn dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ selected_coin.symbol }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-left scrollable">
                                                <span class="dropdown-item"
                                                      v-on:click.prevent="selectSelectedCoin(coin)"
                                                      v-for="coin in filteredCoins">
                                                    <img class="coin-symbol" :src="'/img/coin/16/'+ coin.symbol +'.png'">
                                                    {{ coin.symbol }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="order_error_BUY.order_amount">
                                        {{ order_error_BUY.order_amount }}
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPriceBuy" class="col-sm-2 col-form-label">Price</label>

                                <div class="col-sm-10">
                                    <div class="input-group coin-amount-input">
                                        <input @keypress="handleNumericalInput"
                                               class="form-control form-control decimal-input"
                                               id="inputPriceBuy" placeholder="Price" v-model="order_price_BUY" min="0">
                                        <div class="input-group-btn input-group-append coins-selection">
                                            <button type="button" class="btn dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ transaction_coin.coin_coin }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-left scrollable">
                                                <span class="dropdown-item"
                                                      v-on:click.prevent="set_transaction_coin(trans_coin)"
                                                      v-for="trans_coin in filteredTransactionCoins">
                                                    <img class="coin-symbol" :src="'/img/coin/16/'+ trans_coin.coin_coin +'.png'">
                                                    {{ trans_coin.coin_coin }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="order_error_BUY.order_price">
                                        {{ order_error_BUY.order_price }}
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="form-group row">
                                <label for="inputTotalBuy" class="col-sm-2 col-form-label">Total</label>
                                <div class="col-sm-10">
                                    <div class="input-group coin-amount-input">
                                        <input class="form-control form-control decimal-input" type="text"
                                               id="inputTotalBuy" placeholder="Total" readonly v-model="order_total_BUY">
                                        <div class="input-group-btn input-group-append coins-selection">
                                            <button class="btn represents-total" type="button">
                                                {{ transaction_coin.coin_coin }}
                                            </button>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="order_error_BUY.order_total">
                                        {{ order_error_BUY.order_total }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="order-action-bottom-block">
                            <div class="part">
                                <p>Available
                                    {{ transaction_coin.coin_coin }}:
                                </p>
                                <p class="amount-number">
                                    <strong>{{ wallet_balance }}</strong>
                                </p>
                            </div>
                            <div class="part">
                                <button type="button" class="order-action-button"
                                        v-on:click="save_order('BUY')"
                                        :disabled="buy_price_negative || buy_amount_negative">
                                    Buy
                                    {{ selected_coin.symbol }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="loading" v-if="spinner_BUY"></div>
                    <div class="spinner-bg" v-if="spinner_BUY"></div>
                </div>
            </div>
            <!--/.col-->
            <div class="col-lg-6">
                <div class="spinner-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <div class="box-left">Sell</div>
                        </div>
                        <transition name="fade" >
                            <div class="alert alert-success" role="alert" style="text-align: center; z-index: 1000" v-if="order_success_SELL">
                                <strong>Your order for selling {{ selected_coin.symbol }} {{ order_amount_SELL }}@{{order_price_SELL}} is placed.</strong>
                            </div>
                        </transition>
                        <transition name="fade" >
                            <div v-bind:class="fee_msg_class" role="alert" style="text-align: center; z-index: 1000" v-if="fee_msg_SELL">
                                <strong v-html="fee_msg_SELL"></strong>
                            </div>
                        </transition>
                        <div class="card-block ptb40 order-form">
                            <div class="form-group row input-row">
                                <label for="inputAmountSell" class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-sm-10">
                                    <div class="input-group coin-amount-input">
                                        <input @keypress="handleNumericalInput" name="order_amount_sell"
                                               class="form-control form-control decimal-input" placeholder="Amount"
                                               v-model="order_amount_SELL">
                                        <div class="input-group-btn input-group-append coins-selection">
                                            <button type="button" class="btn dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ selected_coin.symbol }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-left scrollable">
                                                <span class="dropdown-item"
                                                      v-on:click.prevent="selectSelectedCoin(coin)"
                                                      v-for="coin in filteredCoins">
                                                    <img class="coin-symbol" :src="'/img/coin/16/'+ coin.symbol +'.png'">
                                                    {{ coin.symbol }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="order_error_SELL.order_amount"> {{ order_error_SELL.order_amount }}</span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="inputPriceSell" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-10">
                                    <div class="input-group coin-amount-input">
                                        <input @keypress="handleNumericalInput"
                                               class="form-control form-control decimal-input" placeholder="Price"
                                               v-model="order_price_SELL" min="0">
                                        <div class="input-group-btn input-group-append coins-selection">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                                {{ transaction_coin.coin_coin }}
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-left scrollable">
                                                <a class="dropdown-item" href="javascript:void(null)" v-on:click="set_transaction_coin(trans_coin)"
                                                   v-for="trans_coin in filteredTransactionCoins">
                                                    <img class="coin-symbol" :src="'/img/coin/16/'+ trans_coin.coin_coin +'.png'">
                                                    {{ trans_coin.coin_coin }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="order_error_SELL.order_price"> {{ order_error_SELL.order_price }}</span>
                                </div>
                            </div>

                            <br>

                            <div class="form-group row">
                                <label for="inputTotalBuy" class="col-sm-2 col-form-label">Total</label>
                                <div class="col-sm-10">
                                    <div class="input-group coin-amount-input">
                                        <input class="form-control form-control decimal-input" type="text"
                                               placeholder="Total" readonly v-model="order_total_SELL">
                                        <div class="input-group-btn input-group-append coins-selection">
                                            <button class="btn represents-total" type="button">{{ transaction_coin.coin_coin }}</button>
                                        </div>
                                    </div>
                                    <span class="text-danger" v-if="order_error_SELL.order_total"> {{ order_error_SELL.order_total }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-action-bottom-block">
                            <div class="part">
                                <p>Available
                                    {{ selected_coin.symbol }}:
                                </p>
                                <p class="amount-number">
                                    <strong>{{ wallet_orders }}</strong>
                                </p>
                            </div>
                            <div class="part">
                                <button type="button" class="order-action-button"
                                        v-on:click="save_order('SELL')" :disabled="sell_amount_negative || sell_price_negative">
                                    Sell
                                    {{ selected_coin.symbol }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="loading" v-if="spinner_SELL"></div>
                    <div class="spinner-bg" v-if="spinner_SELL"></div>
                </div>
            </div>
        </div>

        <!-- EXCHANGE DISPLAY
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Exchange Options
                    </div>
                    <div class="spinner-wrapper">
                        <div class="card-block">
                            <div class="row p-3 mb-2 bg-light" v-if="Object.keys(exchange_orders).length">
                                <div class="col-sm">
                                    <strong>{{ coin_title }} / {{ market_title }} </strong>
                                </div>
                                <div class="col-sm">
                                    <strong>{{ market_title }}</strong>
                                </div>
                                <div class="col-sm">
                                    <strong>{{ coin_title }}</strong>
                                </div>
                                <div class="col-sm">
                                    Date
                                </div>
                                <div class="col-sm">
                                    &nbsp;
                                </div>
                            </div>
                            <div  v-for = "(item_exchange, index) in exchange_orders">
                                <div class="row p-3 mb-2 text-success" >
                                    <div class="col-sm" >
                                        {{ item_exchange.order_price|format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ item_exchange.order_amount|format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ item_exchange.order_total|format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ item_exchange.updated_at|format_date }}
                                    </div>
                                    <div class="col-sm">
                                        <button type="button" class="btn btn-outline-secondary" v-on:click="exchange_order(item_exchange.order_id, index)" v-if="item_exchange.order_user_id !=  user_id">Accept</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="loader-div-exchange" class="orderbook-exchange-loader"></div>
                        <div id="spinner-exchange" class="orderbook-exchange-spinner"></div>
                    </div>
                </div>
            </div>
        </div>
        -->

        <!-- ORDER DISPLAY -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Orders
                    </div>
                    <div class="spinner-wrapper">
                        <div class="card-block">
                     <span v-for="(order, index) in user_orders">
                     <div class="row p-3 mb-2 bg-light" v-if="index===0">
                         <div class="col-sm">
                             <strong>{{ order.order_maincoin }} / {{ order.order_market }} </strong>
                         </div>
                         <div class="col-sm">
                             <strong>{{ order.order_market }}</strong>
                         </div>
                         <div class="col-sm">
                             <strong>{{ order.order_maincoin }}</strong>
                         </div>
                         <div class="col-sm">
                             &nbsp;
                         </div>
                     </div>
                     <div class="row p-3 mb-2 bg-light" v-else-if="(order.order_maincoin != user_orders[index-1].order_maincoin) || (order.order_market != user_orders[index-1].order_market)">
                         <div class="col-sm">
                             <strong>{{ order.order_maincoin }} / {{ order.order_market }} </strong>
                         </div>
                         <div class="col-sm">
                             <strong>{{ order.order_market }}</strong>
                         </div>
                         <div class="col-sm">
                             <strong>{{ order.order_maincoin }}</strong>
                         </div>
                         <div class="col-sm">
                             &nbsp;
                         </div>
                     </div>

                     <div class="row p-3 mb-2" v-bind:class="{'text-success': order.order_buysell == 1, 'text-danger': order.order_buysell == 2}" >
                         <div class="col-sm" >
                            {{ order.order_price|format_decimals }}
                         </div>
                         <div class="col-sm">
                             {{ order.order_amount|format_decimals }}
                         </div>
                         <div class="col-sm">
                             {{ order.order_total|format_decimals }}
                         </div>
                         <div class="col-sm">
                             <button type="button" class="button btn--xs btn-outline-secondary"
                                     v-on:click="deleteOrder(order.order_id, index)">Delete</button>
                         </div>
                     </div>
                     </span>
                        </div>
                        <div id="loader-div-orders" class=""></div>
                        <div id="spinner-orders" class=""></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- TRANSACTION DISPLAY -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="display: flex;justify-content: space-between;">
                        Executed Orders

                        <div class="executed-orders-switcher">
                            <span>Show sell orders</span>
                            <switcher v-model="showBuyTransactions"
                                      width="50px"
                                      :checked="false"/>
                        </div>
                    </div>
                    <div class="spinner-wrapper">
                        <div class="card-block">
                            <div v-for="(user_transaction, index) in user_transactions">
                                <div class="row p-3 mb-2 bg-light" v-if="index===0">
                                    <div class="col-sm">
                                        <strong>{{ user_transaction.order_maincoin }} / {{ user_transaction.order_market }} </strong>
                                    </div>
                                    <div class="col-sm">
                                        <strong>{{ user_transaction.order_market }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        <strong>{{ user_transaction.order_maincoin }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        Date
                                    </div>
                                </div>
                                <div class="row p-3 mb-2 bg-light" v-else-if="(user_transaction.order_maincoin != user_transactions[index-1].order_maincoin) || (user_transaction.order_market != user_transactions[index-1].order_market)">
                                    <div class="col-sm">
                                        <strong>{{ user_transaction.order_maincoin }} / {{ user_transaction.order_market }} </strong>
                                    </div>
                                    <div class="col-sm">
                                        <strong>{{ user_transaction.order_market }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        <strong>{{ user_transaction.order_maincoin }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        Date
                                    </div>
                                </div>

                                <div class="row p-3 mb-2" v-bind:class="{'text-success': user_transaction.order_buysell == 1, 'text-danger': user_transaction.order_buysell == 2}" >
                                    <div class="col-sm" >
                                        {{ user_transaction.order_price|format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ user_transaction.order_amount|format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ user_transaction.order_total|format_decimals }}
                                    </div>
                                    <div class="col-sm">
                                        {{ user_transaction.update_at|format_date }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="loader-div-transactions" class=""></div>
                        <div id="spinner-transactions" class=""></div>
                    </div>
                </div>
            </div>
        </div>
        <notifications group="notify-user" position="bottom right" />
        <audio ref="audioElm" src="../sounds/transaction-notify.mp3"></audio>
    </div>

</template>

<style type="text/scss" lang="scss">
    .executed-orders-switcher {
        display: flex;
        span {
            padding-right: 10px;
        }
    }


    .sectionTitle {
        color: rgb(33, 39, 111);
        padding: 10px 15px !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        span {
            font-size: 10px;
        }
    }
    .emptyBox {
        height: 92px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;

    }
    .orderbookOuter {
        margin: 0  !important;
    }
    .orderbookHalf>div:nth-child(2n-1){
        background-color: rgba(249,249,253,0.6);
    }
    .orderbookHalf{
        padding-left: 0px;
        padding-right: 0px;
    }
    .orderbookHead{
        border-bottom: 1px solid #e2dff5;
        padding: 0;

        div {
            div {
                height: 60px;
                display: flex;
                align-items: center;

                &:nth-child(1) {
                    color: rgb(70, 71, 234);
                    font-size: 14px;
                }
                &:nth-child(3) {
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    span {
                    font-size: 12px;
                    line-height: 15px;
                        &:nth-child(1) {
                            color: rgb(122, 132, 165);
                        }
                        &:nth-child(2) {
                            color: rgb(36, 43, 113);
                        }
                    }
                }

            }
        }

    }
    .coins-selection {
        font-size: 1em;
    }

    .input-row {
        margin-bottom: 15px !important;
    }

    .scrollable {
        max-height: 300px;
        overflow-y: scroll;
    }
    
    .orderbookTableHead {
        padding: 10px 0px ;
        color: rgb(122, 132, 165);
        font-size: 12px;
    }
    
    .orderbookContent {
        color: rgb(71, 71, 125);
        font-size: 12px;
        .row {
            padding: 5px 0 !important;
        }
    }
    .orderbookContentBar {
        position: absolute;
        top: 0;
        height: 100%;
      
    }
    .orderbookContentBar-buy {
        background-color: #ebfcf3;
        right: 0;
    }
    .orderbookContentBar-sell {
        background-color: #ffecf4;
        left: 0;
    }


    .orderbookContent .row:hover  .orderbookBtnContainer {
       
        display: flex;
    }

    .orderbookBtnContainer {
        position: absolute;
        height: 100%;
        top: 0;
        right: 0;
        display: none;
        align-items: center;
        padding: 0 5px;
    }

    .orderbookBtn {
        display: none;
        border: none;
        width: 20px;
        height: 20px;
        background-color: rgb(222, 225, 243);
        display: flex;
        align-items: center;
        justify-content: center;
        i {
            color: rgb(71, 71, 125);
        }

    }

    .coin-amount-input {
        .btn {
            font-weight: 400;
            background-color: #fff;
            color: #003366;
            border-color: #003366;
            border-left: none;
            border-top: none;
            border-right: none;
            border-radius: 0;

            &:hover {
                background-color: #fff !important;
            }
        }

        .represents-total {
            padding-right: 55px;
            border-color: #003366;
            &:disabled {
                border-color: #003366;
            }

        }

        input {
            background-color: #fff !important;
            border-color: #003366;
            border-right: none;
            border-top: none;
            border-left: none;
            border-radius: 0;

            &:focus {
                border-color: #003366;
                &~.input-group-btn > .btn {
                    border-color: #003366;
                }
            }
        }
    }

    .coin-symbol {
        vertical-align: middle;
    }

    .order-action-bottom-block {
        margin: 0 35px 40px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;

        .amount-number {
            color: #003366;
        }
    }

    .order-action-button {
        width: 150px;
        height: 54px;
        box-shadow: 0 15px 30px rgba(0, 198, 255, 0.3);
        border-radius: 7px;
        background-color: #00c6ff;
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.28px;
        line-height: 54px;
        position: relative;

        &:disabled {
            background-color: #e1e9ee;
            color: #22272f;
            box-shadow: 0 15px 30px #e1e9ee;
        }
    }

    .order-form {
        padding-left: 20px;
        padding-right: 20px;
    }

    label.col-form-label {
        word-wrap: normal;
    }
</style>

<script>
    import {EventBus} from '../app';
    import Switcher from "../components/Switcher";

    let highChart   = require('highcharts/highstock');
    require('highcharts/modules/exporting')(highChart);
    require('highcharts/highcharts-more')(highChart);
    require('highcharts/indicators/indicators')(highChart);
    require('highcharts/indicators/volume-by-price')(highChart);

    let numeral     = require('numeral');
    let moment      = require('moment');
    let math        = require('mathjs');

    const mathJsOptions =  {lowerExp: 0, upperExp: Infinity, notation: 'fixed'};

    export default {
      name: 'orderbook',
      components: {
        Switcher
      },
      data() {
            return {
                user_id: 0,

                totalBuyAmount: 0,
                totalSellAmount: 0,

                transaction_coins: [],
                transaction_coin: {},

                ordersRequest: null,
                chartRequest: null,
                executedOrdersRequest: null,

                coin_title: '',
                market_title: '',
                ob_current: '', //Orderbook current item

                coins: [],
                selected_coin: '',

                min_allowed_value: 0.000000001,

                order_amount_BUY: '',
                order_price_BUY: '',
                order_total_BUY: '',
                trans_type_BUY: '',
                trans_errors_BUY: {},
                wallet_balance: 0,
                coin_available_BUY: '',
                order_success_BUY: false,

                buy_amount_negative: true,
                buy_price_negative: true,

                spinner_BUY: true,
                spinner_SELL: true,

                order_amount_SELL: '',
                order_price_SELL: '',
                order_total_SELL: '',
                trans_type_SELL: '',
                trans_errors_SELL: {},
                wallet_orders: 0,
                coin_available_SELL: '',
                order_success_SELL: false,

                sell_amount_negative: true,
                sell_price_negative: true,

                fee_msg_class: null,
                fee_msg_BUY: null,
                fee_msg_SELL: null,

                exchange_orders: [],

                user_orders: {},
                user_transactions: [],
                order_history: [],

                showBuyTransactions: false,

                order_error_BUY: {},
                order_error_SELL: {},

                /* Storing Latest BUY/SELL action */
                buyList : [],
                sellList : [],

                totalTrades : 0,

                chartInit: true,
                chartData: [],
                chartTotal: 0,
                chartUpDown: {},
                usdPricePerCoin: 0
            }
        },

        created: function() {

            this.set_current_user();
            //Loading main coins.
            this.loadCoins();
            this.fetchUserOrders();
            this.fetchUserTransactions();
            this.fetchOrderHistory();
        },

        destroyed() {
            EventBus.$emit('chatRoomChange', null);
        },

        mounted: function() {

            //initial loading of spinners
            $('#spinner-ob').addClass('spinner-bg');
            $('#loader-div-ob').addClass('loading');

            $('#spinner').addClass('spinner-bg');
            $('#loader-div').addClass('loading');

            $('.orderbook-exchange-spinner').toggleClass('spinner-bg');
            $('.orderbook-exchange-loader').toggleClass('loading');

            $('#spinner-transactions').addClass('spinner-bg');
            $('#loader-div-transactions').addClass('loading');

            //Listening new orders from server
            this.listenOrders();
        },

        watch: {
            showBuyTransactions () {
              this.fetchUserTransactions()
            },
            transaction_coins: function () {

                let default_coin        = this.$route.params.coin !== undefined ? this.$route.params.coin : 'BTC';
                this.transaction_coin	= this.setDefaultCoin( default_coin );
            },

            transaction_coin: function () {

                this.fetchWalletBalance();
                //Loading orderbook only after setting the default main coin.
                this.fetchOrders();
            },

            coins: function () {
                let default_market  = this.$route.params.market !== undefined ? this.$route.params.market : 'ETH';
                this.selected_coin  = this.setSelectedCoin( default_market );

            },

            selected_coin: function () {
                this.chartDisplay();
                this.fetchOrders();
                this.fetchExecutedOrders();
                this.fee_msg_BUY = null;
                this.fee_msg_SELL = null;

                EventBus.$emit('chatRoomChange', this.selected_coin.symbol);
            },

            order_amount_BUY: function () {

                if(this.order_amount_BUY === '') {

                    this.order_total_BUY                = '';
                    this.order_error_BUY.order_amount   = '';
                    return false;
                }

                this.buy_amount_negative    = false;

                if(this.order_total_BUY <= this.wallet_balance) {
                    this.order_error_BUY.order_total = '';
                }

                if(this.computedOrderAmountBuy < this.min_allowed_value) {

                    this.order_error_BUY.order_amount = 'Minimum  amount should be ' + math.format(this.min_allowed_value, mathJsOptions);
                    this.buy_amount_negative    = true;
                    return false;

                }else if(this.computedOrderAmountBuy != '') {
                    this.order_error_BUY.order_amount = '';
                }

                this.calculateBuy();
            },

            order_price_BUY: function () {

                if(this.computedOrderPriceBuy === '') {

                    this.order_total_BUY                = '';
                    this.order_error_BUY.order_price    = '';
                    return false;
                }

                this.buy_price_negative = false;

                if(this.order_error_BUY.order_price !== '') {
                    this.order_error_BUY.order_price = '';
                }

                if(this.order_total_BUY <= this.wallet_balance) {
                    this.order_error_BUY.order_total = '';
                }

                if(this.computedOrderPriceBuy < this.min_allowed_value) {
                    this.order_error_BUY.order_price = 'Minimum price should be ' + math.format(this.min_allowed_value, mathJsOptions);
                    this.buy_price_negative = true;
                    return false;
                }

                this.calculateBuy();
            },

            order_amount_SELL: function () {

                if(this.order_amount_SELL == '') {

                    this.order_total_SELL              = '';
                    this.order_error_SELL.order_amount = '';
                    return false;
                }

                this.sell_amount_negative    = false;

                if(this.computedOrderAmountSell <= this.wallet_orders) {
                    this.order_error_SELL.order_amount = '';
                }

                if(this.computedOrderAmountSell < this.min_allowed_value) {
                    this.order_error_SELL.order_amount = 'Minimum amount should be ' + math.format(this.min_allowed_value, mathJsOptions);
                    this.sell_amount_negative    = true;
                    return false;
                }

                this.calculateSell();
            },

            order_price_SELL: function () {

                if(this.order_price_SELL === '') {

                    this.order_total_SELL              = '';
                    this.order_error_SELL.order_price   = '';
                    return false;
                }

                this.sell_price_negative   = false;

                if(this.computedOrderAmountSell <= this.wallet_orders) {
                    this.order_error_SELL.order_amount = '';
                }

                if(this.computedOrderPriceSell <= this.min_allowed_value ) {
                    this.order_error_SELL.order_price = 'Minimum price should be ' + math.format(this.min_allowed_value, mathJsOptions);
                    this.sell_price_negative   = true;
                    return false;
                } else if(this.computedOrderPriceSell !== '') {
                    this.order_error_SELL.order_price = '';
                }

                this.calculateSell();
            },

            buyList: function () {
                this.totalTrades   = (this.buyList).length + (this.sellList).length;
            },

            sellList: function () {
                this.totalTrades   = (this.buyList).length + (this.sellList).length;
            }
        },

        computed: {
            
            filteredCoins: function() {
                return this.coins.filter(coin => coin.coin_id !== this.transaction_coin.coin_id)
            },
            filteredTransactionCoins: function () {
                return this.transaction_coins.filter(coin => coin.coin_id !== this.selected_coin.coin_id)
            },
            computedOrderPriceBuy: function() {
                return this.order_price_BUY.replace(',', '.')
            },
            computedOrderPriceSell: function() {
                return this.order_price_SELL.replace(',', '.')
            },
            computedOrderAmountBuy: function() {
                return this.order_amount_BUY.replace(',', '.')
            },
            computedOrderAmountSell: function() {
                return this.order_amount_SELL.replace(',', '.')
            },
            buyListSorted: function() {
                return _.orderBy(this.buyList, 'order_price' , 'desc');
            },

            sellListSorted: function() {
                return _.orderBy(this.sellList, 'order_price', 'asc');
            },
            buyListSortedBidsArray () {
                const arr = [];
                this.buyListSorted.forEach(elem => {
                    arr.push(elem.order_total)
                });
                return arr;
            },
            sellListSortedBidsArray () {
                const arr = [];
                this.sellListSorted.forEach(elem => {
                    arr.push(elem.order_total)
                });
                return arr;
            },




            percentageUpDown: function () {

                let grandTotalBuy   = 0;
                let grandTotalSell  = 0;

                for(let i = 0; i < this.buyList.length; i++)
                {
                    grandTotalBuy   = numeral(grandTotalBuy).add(this.buyList[i].order_total).value();
                }

                for(let i = 0; i < this.sellList.length; i++)
                {
                    grandTotalSell   = numeral(grandTotalSell).add(this.sellList[i].order_total).value();
                }

                let percentage  = 0;

                if(grandTotalBuy !=0 && grandTotalSell !=0) {

                    let diff    = numeral(grandTotalSell).subtract(grandTotalBuy).value();
                    let div     = numeral(diff).divide(grandTotalBuy).value();

                    percentage  = numeral(div).multiply(100).value();
                }

                let clsSign = percentage < 0 ? 'fa-arrow-circle-o-down text-danger' : 'fa-arrow-circle-o-up text-success';
                clsSign     = (isNaN(percentage) || percentage ==0) ? '' : clsSign;

                return {

                    updown : isNaN(percentage) ? '' : Math.abs(numeral(percentage).format('0.00')) + '%',
                    sign: clsSign
                };
            },
        },

        filters: {

            format_date: function ( value ) {
                return moment(value).format('DD-MM-YYYY HH:mm:ss');
            },

            format_decimals: function ( value ) {

                let to_display  = numeral(value).format('0.000000000');
                return isNaN(to_display) ? '0.000000000' : to_display;
            },

            nodecimal_places: function ( value ) {

                return numeral(value).format('0');
            }
        },

        methods: {
            // normalizeArray(array, currentValue, margin) {
            //     const maxNumberAndMargin = margin ? Math.max(...array) * (1 + margin) : Math.max(...array) * 1.2;
            //     return currentValue / maxNumberAndMargin * 100;
            // },
            relativeWidth(array, index) {
                const sumTotal = array.reduce(function(acc, val) { 
                    return acc + Number(val); 
                }, 0);

                let sumCurrent = 0;
                for (let i = 0; i <= index; i++) {
                    sumCurrent+= Number(array[i])
                }
                return sumCurrent / sumTotal * 100;
            },
            handleNumericalInput: function (e) {
                const isIgnoreKey = [8, 9, 37, 39, 46].indexOf(e.keyCode) > -1;
                if (isIgnoreKey) {
                    return true;
                }

                const value = e.target.value + e.key;
                const isKeyValid = e.key.match(/[\d\,\.]/);
                const isValueValid = e.target.value.length > 1 ? value.match(/^\d+([\.\,]{0,1})(\d+)?$/) : true;

                if (!isKeyValid || !isValueValid) {
                    e.preventDefault();
                }
            },

            takeOrder: function (order) {
                const type = order.order_buysell === '1' ? 'SELL' : 'BUY';
                this['order_amount_' + type] = order.order_amount;
                this['order_price_' + type] = order.order_price;
                this.save_order(type);
                $('html, body').animate({
                    scrollTop: $("#buy-or-sell").offset().top
                }, 300);
            },

            selectSelectedCoin: function (coin) {
                this.selected_coin = coin;
                this.fee_msg_BUY = null;
                this.fee_msg_SELL = null;
            },

            listenOrders: function () {

                Echo.channel('update-order-vue')
                    .listen('.ordersaved', data => {

                        let order   = data[this.ob_current].order;
                        console.log(order);
                        if(data.hasOwnProperty( this.ob_current )) {

                            if(data[this.ob_current].type == 1) {

                                this.buyList.push( order );

                                if(
                                    (this.transaction_coin.coin_coin == order.order_maincoin) &&
                                    (this.selected_coin.symbol == order.order_market) &&
                                    (order.order_exchange == 1)
                                ) {

                                    this.exchange_orders.push( order );
                                }
                            }
                            else {

                                this.sellList.push( order );
                            }

                            if(order.order_user_id == this.user_id) {

                                this.user_orders.push( order );
                            }

                        }
                    })
                    .listen('.orderdeleted', data => {

                        if(data['order_buysell'] == 1) {

                            let index   = this.indexWhere(this.buyList, item => item.order_id == data['order_id']);

                            Vue.delete(this.buyList, index);

                        } else {
                            let index   = this.indexWhere(this.sellList, item => item.order_id == data['order_id']);

                            Vue.delete(this.sellList, index);
                        }
                    })
                    .listen('.user_transactions', data => {

                        for(let i in data) {

                            if(data[i].user_id === this.user_id) {

                                data[i].update_at   = moment.unix(data[i].update_at).format('YYYY-MM-DD HH:mm:ss');
                                data[i].order_total = numeral(data[i].order_price).multiply(data[i].order_amount).value();

                                let order_row       = this.indexWhere(this.user_orders, item => item.order_id == data[i]['order_id']);

                                let new_order_amount    = numeral(this.user_orders[order_row].order_amount).sub(data[i]['order_amount']).value();

                                if(data[i]['order_buysell'] == 1) {

                                    this.wallet_orders      = numeral(this.wallet_orders).add(data[i]['order_amount']).value();
                                }


                                if(new_order_amount === 0) {

                                    Vue.delete(this.user_orders, order_row);
                                } else {

                                    this.user_orders[order_row].order_amount    = new_order_amount;
                                    this.user_orders[order_row].order_total     = numeral(new_order_amount).multiply(this.user_orders[order_row].order_price).value();
                                }

                                this.user_transactions.push(data[i]);

                                this.$refs.audioElm.play();
                                this.$notify({
                                    group: 'notify-user',
                                    type: 'success',
                                    title: 'Transaction alert - ' + data[i]['order_market'],
                                    text: 'Amt: ' + data[i]['order_amount'] + ' - @' + data[i]['order_price'] + ' - ' + data[i]['order_maincoin'],
                                    duration: 5000,
                                    speed: 100
                                })
                            }
                        }
                    })
                    .listen('.hide_exchange_selection', data => {

                        //Hiding the selected order from all open windows.
                        let index   = this.indexWhere(this.exchange_orders, item => item.order_id == data['order_id']);
                        Vue.delete(this.exchange_orders, index);

                        if(this.selected_coin.symbol == data['market']) {

                            //For user who put exchange order
                            if(this.user_id == data['order_user_id']) {

                                this.wallet_orders = numeral(this.wallet_orders).add(data['amount']).value();
                            }

                            //For user who is accepting exchange order
                            if(this.user_id == data['sell_user_id']) {

                                this.wallet_orders = numeral(this.wallet_orders).subtract(data['amount']).value();
                            }
                        }
                    })
            },

            indexWhere: function(array, conditionFn) {
                const item = array.find(conditionFn);
                return array.indexOf(item)
            },

            set_current_user: function () {

                $.ajax({
                    type: "GET",
                    url: "/set-user",
                    cache: false,
                    success: function(response, status, error) {

                        this.user_id = response.user_id;
                    }.bind(this),

                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },

            getTransactionCoins: function () {

                $.ajax({
                    type: "GET",
                    url: "/vue-dashboard/coins",
                    cache: false,
                    success: function(response, status, error) {

                        this.transaction_coins = response;

                    }.bind(this),

                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },

            setDefaultCoin: function( symbol )
            {
                var response = _.find(this.transaction_coins, { coin_coin: symbol });
                if (response === undefined) {
                    response = {};
                }
                return response;
            },

            setSelectedCoin: function( symbol )
            {
                return _.find(this.coins, { symbol: symbol });
            },

            set_transaction_coin: function ( coin ) {

                this.transaction_coin	= coin;
                this.fee_msg_BUY        = null;
                this.fee_msg_SELL       = null;
            },

            loadCoins: function () {

                $.ajax({
                    type: "GET",
                    url: '/vue-dashboard/fetch-ids-symbols',
                    success: (response, status, error) => {

                        this.coins  = response;
                        //Loading maincoins only after market loaded
                        this.getTransactionCoins();
                    },
                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },

            fetchOrders: function () {

                //Not fetching when market and main coin are same
                if(this.selected_coin.symbol === this.transaction_coin.coin_coin) {

                    this.buyList            = [];
                    this.sellList           = [];
                    return false;
                }

                this.exchange_orders    = [];

                if(!$('.orderbook-exchange-spinner').hasClass('spinner-bg')) {

                    $('.orderbook-exchange-spinner').toggleClass('spinner-bg');
                    $('.orderbook-exchange-loader').toggleClass('loading');

                }

                let data  = {
                    'market': this.selected_coin.symbol,
                    'coin': this.transaction_coin.coin_coin
                };

                this.ob_current = this.transaction_coin.coin_coin + '-' + this.selected_coin.symbol;

                let self    = this;

                self.ordersRequest  = $.ajax({
                    type: "POST",
                    data: data,
                    catch: false,
                    url: '/fetch-orders',
                    beforeSend: function () {

                        if(self.ordersRequest != null) {

                            //Aborting the same previous request if exists
                            self.ordersRequest.abort();
                            self.ordersRequest  = null;
                        }
                    },
                    success: function(response, status, error) {
                        self.totalBuyAmount = response.total_buy_amount;
                        self.totalSellAmount = response.total_sell_amount;
                        self.coin_title     = data.coin;
                        self.market_title   = data.market;
                        if (response && response[self.ob_current]) {
                            self.buyList    = response[self.ob_current]['BUY'];
                            self.sellList   = response[self.ob_current]['SELL'];
                        } else {
                            self.buyList    = [];
                            self.sellList   = [];
                        }


                        for(let i in self.buyList) {

                            if( self.buyList[i].order_exchange == 1 && self.buyList[i].order_executed == 0) {

                                self.exchange_orders.push( self.buyList[i] );
                            }
                        }

                        $('.orderbook-exchange-spinner').toggleClass('spinner-bg');
                        $('.orderbook-exchange-loader').toggleClass('loading');

                    },
                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },

            calculateBuy: function  () {
                this.order_error_BUY.order_total = '';

                const buyAmount = numeral(this.computedOrderAmountBuy || 0);

                let total               = buyAmount.multiply(this.computedOrderPriceBuy || 0).value();

                this.order_total_BUY    = math.format(total, mathJsOptions);

                if(total < this.min_allowed_value)
                {
                    this.order_error_BUY.order_total  = "Minimum amount allowed in order total is " + math.format(this.min_allowed_value, mathJsOptions);
                    this.buy_price_negative = true;
                    return false;
                }

                if(total > this.wallet_balance)
                {
                    this.order_error_BUY.order_total  = "Total amount exceeds the wallet balance";
                    this.buy_price_negative = true;
                    return false;
                }
            },

            calculateSell: function  () {
                this.order_error_SELL.order_total = '';

                const sellAmount = numeral(this.computedOrderAmountSell || 0);
                if (sellAmount.value() > this.wallet_orders) {
                    this.sell_amount_negative = true;
                    return Vue.set(this.order_error_SELL, 'order_amount', 'Amount exceeds the wallet balance');
                }

                let total = sellAmount.multiply(this.computedOrderPriceSell || 0).value();

                this.order_total_SELL   = math.format(total, mathJsOptions);
                if(total < this.min_allowed_value)
                {
                    this.order_error_SELL.order_total  = "Minimum amount allowed in order total is " + math.format(this.min_allowed_value, mathJsOptions);
                    this.sell_price_negative = true;
                    return false;
                }

                if(total > this.wallet_orders)
                {
                    Vue.set(this.order_error_SELL, 'order_amount', "Total quantity exceeds the wallet balance");
                    this.sell_price_negative = true;
                    return false;
                }
            },

            save_order: function ( type ) {

                this['fee_msg_' + type] = null;

                if(this.selected_coin.symbol === this.transaction_coin.coin_coin) {

                    this.showAlert();
                    return false;
                }

                console.log(this.wallet_balance, type);
                if(this.wallet_balance == 0 && type == 'BUY') {

                    this.loadWalletAlert();
                    return false;
                }

                if( this.wallet_orders == 0 && type == 'SELL')
                {
                    this.$swal({

                        title: 'Not Available',
                        text: "You don't have any purchased " + this.selected_coin.symbol,
                        type: 'warning',
                        cancelButtonText: 'Ok Got It !',
                        showCancelButton: true,
                        showConfirmButton: false

                    }).catch(this.$swal.noop);

                    return false;
                }

                if(this[ 'order_amount_' + type ] === '') {
                    Vue.set(this[ 'order_error_' + type ], 'order_amount', "Please fill your required amount");
                    return false;
                }

                if(this[ 'order_price_' + type ] === '') {
                    Vue.set(this[ 'order_error_' + type ], 'order_price', "Please fill your price");
                    return false;
                }

                let self    = this;

                this.$swal({

                    title: 'Confirm your ' + this.selected_coin.symbol + ' Order',
                    text: 'Amount: ' +  this[ 'order_amount_' + type ] + ' ' + this.selected_coin.symbol + ' @' + this[ 'order_price_' + type ] + '-' + this.transaction_coin.coin_coin,
                    type: 'info',
                    cancelButtonText: 'Edit Order',
                    showCancelButton: true,
                    confirmButtonText: (type === 'BUY' ? 'BUY ' : 'SELL ') + this.selected_coin.symbol

                }).then(function(result){

                    if(result.hasOwnProperty('dismiss')) return false;

                    self['spinner_' + type] = true;

                    let order = {

                        order_market: self.selected_coin.symbol,
                        order_amount: self[ 'order_amount_' + type ],
                        order_price: self[ 'order_price_' + type ],
                        order_total: self['order_total_' + type],
                        order_maincoin: self.transaction_coin.coin_coin,
                        order_maincoin_id: self.transaction_coin.coin_id,
                        order_coin_id: self.selected_coin.coin_id,
                        wallet_balance: type === 'BUY' ? self.wallet_balance : self.wallet_orders,
                        order_type: type === 'BUY' ? 1 : 2
                    };

                    $.ajax({
                        type: "POST",
                        url: '/saveorders',
                        data: order,
                        cache: false,
                        success: function(response, status, error) {


                            if(response.gas_error) {

                                self.fee_msg_class      = 'alert alert-danger';
                                self['fee_msg_' + type] = "We are unable to process your request, <a href=\"./contact\">Click Here</a> to report this issue";
                                self['spinner_' + type] = false;
                                return false;
                            } else if(response.fee_error) {

                                self.fee_msg_class      = 'alert alert-danger';

                                let field   = type == 'BUY' ? 'total' : 'amount';
                                self['fee_msg_' + type] = 'Not enough ' + field + ' to pay the transaction fee of ' + response.req_amt;
                                self['spinner_' + type] = false;
                                return false;
                            } else if( response.errors){

                                self[ 'order_error_' + type ]    = self.formatValidatorErrors(response.errors);
                                self['spinner_' + type] = false;
                            }
                            else {

                                if(type === 'BUY') {
                                    self.wallet_balance = numeral(self.wallet_balance).subtract(response.order_total).value();
                                }
                                else {
                                    self.wallet_orders = numeral(self.wallet_orders).subtract(response.order_amount).value();
                                }

                                self[ 'order_success_' + type ] = true;

                                setTimeout(function () {

                                    self[ 'order_success_' + type ] = false;
                                    self[ 'order_amount_' + type ]  = '';
                                    self[ 'order_price_' + type ]   = '';
                                    self[ 'order_total_' + type ]   = '';

                                    self['spinner_' + type] = false;

                                    self.fetchUserOrders();
                                    self.fetchOrders();
                                    self.fetchUserTransactions();
                                    self.fetchOrderHistory();
                                }, 5000);
                            }
                        },
                        error: function(data,status,error){
                            console.log(error);
                        }
                    });
                }).catch(this.$swal.noop);
            },

            exchange_order: function ( id, index ) {

                this.$swal({

                    title: 'Are you sure?',
                    text: 'You want to Accept this Exchange',

                    type: 'info',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, I am !',
                    cancelButtonText: 'No, I don\'t want'

                }).then(( result ) => {

                    if(result.hasOwnProperty('dismiss')) return false;

                    $.ajax({
                        type: "POST",
                        url: '/exchange-orders',
                        data: {
                            order_id: id
                        },
                        success: function(response, status, error) {

                            if(response) {
                                Vue.delete( this.exchange_orders, index );
                            }

                        }.bind(this),
                        error: function(data,status,error){
                            console.log(error);
                        }
                    });

                }).catch(this.$swal.noop);
            },

            loadWalletAlert: function () {
                const self = this;
                this.$swal({

                    title: 'ZERO BALANCE',
                    text: 'Your '+ this.transaction_coin.coin_coin +' balance is ZERO. Please load your wallet.',
                    type: 'warning',
                    cancelButtonText: 'Ok Got It !',
                    showCancelButton: true,
                    confirmButtonText: 'Go to Wallet Page'

                }).then( function(result) {
                    if (result.hasOwnProperty('dismiss')) return false;

                    self.$router.push({name: 'Wallet'});
                }).catch(this.$swal.noop);
            },

            showAlert: function () {

                let self	= this;

                this.$swal({

                    title: 'Invalid Operation',
                    text: 'Select another coin. You can\'t buy or sell '+ this.selected_coin.symbol +' with '+ this.transaction_coin.coin_coin +' itself!',
                    type: 'info',
                    cancelButtonText: 'Ok Got It !',
                    showCancelButton: true,
                    showConfirmButton: false

                }).catch(this.$swal.noop);
            },

            fetchUserOrders: function () {

                $.ajax({
                    type: "GET",
                    url: '/vue-dashboard/fetch-user-orders',
                    success: function(response, status, error) {
                        this.user_orders  = response;

                        $('#spinner-orders').removeClass('spinner-bg');
                        $('#loader-div-orders').removeClass('loading');
                    }.bind(this),
                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },

            fetchUserTransactions: function () {
                $.ajax({
                    type: "GET",
                    url: '/fetch-user-transactions',
                    data: {show_sell: this.showBuyTransactions ? 1 : 0},
                    success: function(response, status, error) {

                        Vue.set(this, 'user_transactions', response);

                        $('#spinner-transactions').removeClass('spinner-bg');
                        $('#loader-div-transactions').removeClass('loading');
                    }.bind(this),
                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            fetchOrderHistory: function () {
                $.ajax({
                    type: "GET",
                    url: '/fetch-order-history',
                    success: function (response, status, error) {

                        Vue.set(this, 'order_history', response);

                        $('#spinner-transactions').removeClass('spinner-bg');
                        $('#loader-div-transactions').removeClass('loading');
                    }.bind(this),
                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },

            deleteOrder: function (id, index) {

                this.$swal({

                    title: 'Are you sure?',
                    text: 'You want to delete this Order',

                    type: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'

                }).then((result) => {

                    if(result.hasOwnProperty('dismiss')) return false;

                    $.ajax({
                        type: "POST",
                        url: '/vue-dashboard/delete-order',
                        data: {
                            order_id: id
                        },
                        success: (response, status, error) => {

                            if(response.delete) {

                                if (response.type == 1) {

                                    this.wallet_balance = numeral(this.wallet_balance).add(response.amount).value();
                                } else {

                                    this.wallet_orders  = numeral(this.wallet_orders).add(response.amount).value();
                                }
                                Vue.delete( this.user_orders, index );

                                const cond = (x) => x.order_id == id;
                                Vue.delete(
                                    this.sellList,
                                    this.indexWhere(this.sellList, cond)
                                );
                                Vue.delete(
                                    this.buyList,
                                    this.indexWhere(this.buyList, cond)
                                );
                            }

                        },
                        error: function(data,status,error){
                            console.log(error);
                        }
                    });

                }).catch(this.$swal.noop);
            },

            fetchWalletBalance: function () {

                let post_data = {coin_id: this.transaction_coin.coin_id};

                $.ajax({
                    type: "POST",
                    url: '/vue-dashboard/fetch-wallet',
                    data: post_data,
                    cache: false,
                    success: (response, status, error) => {

                        let amount  = response.amount  == null ? 0 : response.amount;
                        let inorder = response.inorder == null ? 0 : response.inorder;
                        this.wallet_balance = numeral(amount).subtract(inorder).value();

                        this.spinner_BUY    = false;
                    },
                    error: function(data,status,error){
                        console.log(data);
                    }
                });
            },

            fetchExecutedOrders: function () {

                let post_data = {coin_id: this.selected_coin.coin_id};
                let self    = this;

                self.executedOrdersRequest   = $.ajax({
                    type: "POST",
                    url: '/vue-dashboard/fetch-orders-executed',
                    data: post_data,
                    cache: false,
                    beforeSend: function () {


                        if(self.executedOrdersRequest != null) {

                            //Aborting the same previous request if exists
                            self.executedOrdersRequest.abort();
                            self.executedOrdersRequest  = null;
                        }
                    },
                    success: (response, status, error) => {

                        let amount  = response.amount  == null ? 0 : response.amount;
                        let inorder = response.inorder == null ? 0 : response.inorder;

                        this.wallet_orders  = numeral(amount).subtract(inorder).value();
                        this.spinner_SELL   = false;
                    },
                    error: function(data,status,error){
                        console.log(error);
                    }
                });
            },

            chartDisplay: function () {

                $('#spinner').addClass('spinner-bg');
                $('#loader-div').addClass('loading');

                let self    = this;

                let post_data = {
                    symbol: this.selected_coin.symbol,
                    coin_id: this.selected_coin.coin_id
                };


                self.chartRequest   = $.ajax({
                    type: "GET",
                    url: '/chartdata',
                    data: post_data,
                    cache: false,
                    beforeSend: function () {

                        if(self.chartRequest != null) {

                            //Aborting the same previous request if exists
                            self.chartRequest.abort();
                            self.chartRequest  = null;
                        }
                    },
                    success: function(response, status, error) {

                        Vue.set(this, 'chartData', response.data);
                        Vue.set(this, 'chartTotal', response.total);
                        this.usdPricePerCoin = response.price.usd;

                        let clsSign = response.updown < 0 ? 'fa-arrow-circle-o-down text-danger' : 'fa-arrow-circle-o-up text-success';
                        clsSign     = isNaN(response.updown) || (response.updown == 0)  ? '' : clsSign;

                        this.chartUpDown    =  {

                            updown : isNaN(response.updown ) ? '' : Math.abs(numeral(response.updown ).format('0.00')) + '%',
                            sign: clsSign
                        };

                        let  groupingUnits = [[
                            'week',                         // unit name
                            [1]                             // allowed multiples
                        ], [
                            'month',
                            [1, 2, 3, 4, 6]
                        ]];

                        let self    = this;
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
                            exporting: { enabled: false },

                            series: [{
                                type: 'candlestick',
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

                                        if(event.hasOwnProperty('DOMEvent') && event.DOMEvent.type !== 'mouseup') {
                                            return false;
                                        } //continue only in mouse release

                                        $.ajax({
                                            type: "POST",
                                            url: '/chartzoom',
                                            data: post_data,
                                            cache: false,
                                            success: function(response, status, error) {

                                                self.chartTotal = response.total;

                                                let clsSign = response.updown < 0 ? 'fa-arrow-circle-o-down text-danger' : 'fa-arrow-circle-o-up text-success';
                                                clsSign     = isNaN(response.updown) || (response.updown == 0)  ? '' : clsSign;

                                                self.chartUpDown    =  {
                                                    updown : isNaN(response.updown ) ? '' : Math.abs(numeral(response.updown ).format('0.00')) + '%',
                                                    sign: clsSign
                                                };
                                            }.bind(this),
                                            error: function(data,status,error){
                                                console.log(error);
                                            }
                                        });
                                    }
                                }
                            }
                        });
                        $('.highcharts-credits').hide();

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

                for(let i in errors) {

                    formatted[i]    = errors[i][0];
                }

                return formatted;
            }

        },

    }
</script>