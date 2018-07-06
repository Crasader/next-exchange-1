<template>
    <div class="animated fadeIn container-fluid" style="padding: 0;">
        <div class="card spinner-wrapper">
            <div class="container" id="wallet" style="padding: 0;">
                <!-- <div class="container" v-if="add_wallet===0" id="wallet"> -->
                <!-- <div class="container-fluid m-2 text-center text-primary"><h3><i class="fa fa-plus"
                                                                                 style="cursor: pointer"
                                                                                 title="Add Wallet"
                                                                                 v-on:click="switch_page(1)"></i></h3>
                </div> -->

                <div class="row m-2 p-2 table__title">
                    <!-- <div class="col-sm-6">
                        <v-select class="wallet__select" v-model="selected_coin" label="coin_title" :options="coins">
                            <template slot="option" slot-scope="option">
                                <img class="wallet__select__image" :src="'/img/coin/32/'+option.symbol+'.png'" onerror="this.src='/img/coin/32/noimage.png'">
                                <span class="wallet__select__text">{{ option.coin_title }}</span>
                            </template>
                        </v-select>
                    </div> -->
                    <!-- <div class="col-sm-6">
                        <div class="spinner-wrapper">
                            <div class="row" v-if="selected_coin!=''">
                                <div class="col-sm-2"><img v-bind:src="'/img/coin/32/'+selected_coin.symbol+'.png'"
                                                           onerror="this.src='/img/coin/32/noimage.png'"></div>
                                <div class="col-sm-7">{{ selected_coin.coin_title }}</div>
                                <div class="col-sm-3">
                                    <label class="switch">
                                        <input v-model="selected_option" type="checkbox"
                                               v-bind:checked="selected_option">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="loading" v-if="add_wallet_spinner"></div>
                            <div class="spinner-bg" v-if="add_wallet_spinner"></div>
                        </div>
                    </div> -->

                    <div class="col-lg-2 col-3" style="justify-content: flex-start">Wallets</div>
                    <div class="col-lg-1 hidden-mobile" style="justify-content: flex-end; padding: 0"><img
                            v-if="selected_coin!='Select coin:'"
                            v-bind:src="'/img/coin/32/'+selected_coin.symbol+'.png'"
                            onerror="this.src='/img/coin/32/noimage.png'"></div>
                    <div class="col-lg-6 col-6" style="justify-content: center">
                        <v-select class="wallet__select" v-model="selected_coin" label="coin_title" :options="coins">
                            <template slot="option" slot-scope="option">
                                <img class="wallet__select__image" :src="'/img/coin/32/'+option.symbol+'.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                                <span class="wallet__select__text">{{ option.coin_title }}</span>
                            </template>
                        </v-select>
                    </div>
                    <div class="col-lg-1 hidden-mobile"></div>
                    <div class="col-lg-2 col-3" style="justify-content: flex-end">
                        <!-- <label v-if="selected_coin!=''" class="switch">
                            <input v-model="selected_option" type="checkbox"
                                    v-bind:checked="selected_option">
                            <span class="slider round"></span>
                        </label> -->


                        <button class="table__title__btn"
                                :class="selected_option ? 'table__title__btn-red' : 'table__title__btn-grey'"
                                v-if="selected_coin!='Select coin:'" @click="selected_option = !selected_option">
                            {{selected_option ? 'Remove' : 'Add'}}
                        </button>
                    </div>


                </div>

                <div class="row bg-white m-2 p-2 coin" v-if="Object.keys(BTC).length">

                    <!-- BTC TOKENS -->
                    <div class="row col-12" v-for="current_coin in BTC">
                        <div class="row col-12" v-if="current_coin.maintenance_mode==false && current_coin.coin_id==1">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-2">
                                {{ current_coin.available }}
                            </div>


                            <div class="col-sm-8">
                                <div class="input-group ">
                                    <input class="form-control" placeholder="Address" type="text"
                                           :id="'address_' + current_coin.coin_id" :value="current_coin.address"
                                           readonly/>
                                    <div class="">
                                        <button class="action-button" v-clipboard="current_coin.address"
                                                v-on:click="content_copied(current_coin.coin_id)" title="Copy Address">
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                        <button class="action-button" type="button" title="Withdraw"
                                                v-on:click="show_withdraw( current_coin )">
                                            <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                            <i class="fa fa-level-down" v-else></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!--Withdraw and error display-->
                            <div class="input-group col-sm-12" v-if="current_coin.show_withdraw">
                                <input class="form-control" placeholder="Address" type="text"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_address">
                                <input class="form-control"
                                       :placeholder="'Amount (Limit : ' +  [current_coin.daily_limit<0 ? 'Unlimited' : current_coin.daily_limit] + ')'"
                                       type="number"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_amount">
                                <span class="input-group-btn">
                                    <button class="action-button" type="button"
                                            v-on:click="withdraw( current_coin, '1' )"
                                            :disabled="current_coin.btn_disabled">
                                        Withdraw >>
                                    </button>
                                </span>
                            </div>

                            <div class="col-sm-12" v-if="current_coin.show_data == 0">
                                <br/>
                                <span class="text-muted">Amount: {{ current_coin.payment_amount }}</span><br>
                                <span class="text-warning">Fees: {{ current_coin.total_fee }}</span><br>
                                <span class="text-success">Creditable amount: <b>{{ current_coin.credit_amount }}</b></span><br>
                                <!--div style="text-align: right">
                                    <button class="btn btn-info" v-on:click="withdraw( current_coin, '2' )" :disabled="current_coin.btn_disabled" >Accept <i class="fa fa-circle-o-notch fa-spin" v-if="current_coin.btn_disabled"></i></button>
                                </div-->
                            </div>
                            <div class="col-sm-12" :class="current_coin.class" v-if="current_coin.error">{{
                                current_coin.error }}
                            </div>
                            <!--Withdraw and error display Ends-->
                        </div>
                        <div class="row col-12 clearfix" v-else-if="current_coin.maintenance_mode==false">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-2">
                                {{ current_coin.available }}
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">{{ current_coin.address }}</span>
                            </div>
                            <div class="col-sm-2">
                            <span class="float-right">
                            <div class="input-group">
                            <div class="">
                                <button class="action-button" type="button" title="Withdraw"
                                        v-on:click="show_withdraw( current_coin )">
                                    <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                    <i class="fa fa-level-down" v-else></i>
                                </button>
                            </div>
                            </div>
                            </span>
                            </div>
                            <!--Withdraw and error display-->
                            <div class="input-group col-sm-12" v-if="current_coin.show_withdraw">
                                <input class="form-control" placeholder="Address" type="text"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_address"><input class="form-control"
                                                                                     :placeholder="'Amount (Limit : ' +  [current_coin.daily_limit<0 ? 'Unlimited' : current_coin.daily_limit] + ')'"
                                                                                     type="number"
                                                                                     v-on:keyup="clear_error( current_coin )"
                                                                                     v-model="current_coin.payment_amount"><span
                                    class="input-group-btn"><button class="btn btn-withdraw" type="button"
                                                                    v-on:click="withdraw( current_coin, '1' )"
                                                                    :disabled="current_coin.btn_disabled">Withdraw >></button>
                  </span>
                            </div>
                            <div class="col-sm-12" v-if="current_coin.show_data == 0">
                                <br/>
                                <span class="text-muted">Amount: {{ current_coin.payment_amount }}</span><br>
                                <span class="text-warning">Fees: {{ current_coin.total_fee }}</span><br>
                                <span class="text-success">Creditable amount: <b>{{ current_coin.credit_amount }}</b></span><br>
                                <!--div style="text-align: right">
                                    <button class="btn btn-info" v-on:click="withdraw( current_coin, '2' )" :disabled="current_coin.btn_disabled" >Accept <i class="fa fa-circle-o-notch fa-spin" v-if="current_coin.btn_disabled"></i></button>
                                </div-->
                            </div>
                            <div class="col-sm-12" :class="current_coin.class" v-if="current_coin.error">{{
                                current_coin.error }}
                            </div>
                            <!--Withdraw and error display Ends-->
                        </div>
                        <div class="row col-12" v-if="current_coin.maintenance_mode==true">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-10 text-warning">Wallet under Maintenance</div>
                        </div>
                    </div>
                </div>

                <div class="row bg-white m-2 p-2 coin" v-if="Object.keys(ETH).length">

                    <!-- ETH TOKENS -->
                    <div class="row col-12" v-for="current_coin in ETH">
                        <div class="row col-12" v-if="current_coin.maintenance_mode==false && current_coin.coin_id==2">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-2">
                                {{ current_coin.available }}
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input class="form-control" placeholder="Address" type="text"
                                           :id="'address_' + current_coin.coin_id" :value="current_coin.address"
                                           readonly/>
                                    <div class="">
                                        <button class="action-button" v-clipboard="current_coin.address"
                                                v-on:click="content_copied(current_coin.coin_id)" title="Copy Address">
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                        <button class="action-button" type="button" title="Withdraw"
                                                v-on:click="show_withdraw( current_coin )">
                                            <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                            <i class="fa fa-level-down" v-else></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!--Withdraw and error display-->
                            <div class="input-group col-sm-12" v-if="current_coin.show_withdraw">
                                <input class="form-control" placeholder="Address" type="text"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_address">
                                <input class="form-control"
                                       :placeholder="'Amount (Limit : ' +  [current_coin.daily_limit<0 ? 'Unlimited' : current_coin.daily_limit] + ')'"
                                       type="number"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_amount">
                                <div class="">
                                    <button class="btn btn-withdraw" type="button"
                                            v-on:click="withdraw( current_coin, '1' )"
                                            :disabled="current_coin.btn_disabled">
                                        Withdraw >>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-12" v-if="current_coin.show_data == 0">
                                <br/>
                                <span class="text-muted">Amount: {{ current_coin.payment_amount }}</span><br>
                                <span class="text-warning">Fees: {{ current_coin.total_fee }}</span><br>
                                <span class="text-success">Creditable amount: <b>{{ current_coin.credit_amount }}</b></span><br>
                                <!--div style="text-align: right">
                                    <button class="btn btn-info" v-on:click="withdraw( current_coin, '2' )" :disabled="current_coin.btn_disabled" >Accept <i class="fa fa-circle-o-notch fa-spin" v-if="current_coin.btn_disabled"></i></button>
                                </div-->
                            </div>
                            <div class="col-sm-12" :class="current_coin.class" v-if="current_coin.error">{{
                                current_coin.error }}
                            </div>
                            <!--Withdraw and error display Ends-->
                        </div>
                        <div class="row col-12 clearfix" v-else-if="current_coin.maintenance_mode==false">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-2">
                                {{ current_coin.available }}
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">{{ current_coin.address }}</span>
                            </div>
                            <div class="col-sm-2">
                            <span class="float-right">
                            <div class="input-group">
                            <div class="">
                                <button class="action-button" type="button" title="Withdraw" v-on:click="show_withdraw( current_coin )">
                                    <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                    <i class="fa fa-level-down" v-else></i>
                                </button>
                            </div>
                            </div>
                            </span>
                            </div>
                            <!--Withdraw and error display-->
                            <div class="input-group col-sm-12" v-if="current_coin.show_withdraw">
                                <input class="form-control" placeholder="Address" type="text"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_address"><input class="form-control"
                                                                                     :placeholder="'Amount (Limit : ' +  [current_coin.daily_limit<0 ? 'Unlimited' : current_coin.daily_limit] + ')'"
                                                                                     type="number"
                                                                                     v-on:keyup="clear_error( current_coin )"
                                                                                     v-model="current_coin.payment_amount"><span
                                    class="input-group-btn"><button class="btn btn-withdraw" type="button"
                                                                    v-on:click="withdraw( current_coin, '1' )"
                                                                    :disabled="current_coin.btn_disabled">Withdraw >></button>
                  </span>
                            </div>
                            <div class="col-sm-12" v-if="current_coin.show_data == 0">
                                <br/>
                                <span class="text-muted">Amount: {{ current_coin.payment_amount }}</span><br>
                                <span class="text-warning">Fees: {{ current_coin.total_fee }}</span><br>
                                <span class="text-success">Creditable amount: <b>{{ current_coin.credit_amount }}</b></span><br>
                                <!--div style="text-align: right">
                                    <button class="btn btn-info" v-on:click="withdraw( current_coin, '2' )" :disabled="current_coin.btn_disabled" >Accept <i class="fa fa-circle-o-notch fa-spin" v-if="current_coin.btn_disabled"></i></button>
                                </div-->
                            </div>
                            <div class="col-sm-12" :class="current_coin.class" v-if="current_coin.error">{{
                                current_coin.error }}
                            </div>
                            <!--Withdraw and error display Ends-->
                        </div>
                        <div class="row col-12" v-if="current_coin.maintenance_mode==true">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-10 text-warning">Wallet under Maintenance</div>
                        </div>
                    </div>
                </div>

                <div class="row bg-white m-2 p-2 coin" v-if="Object.keys(XEM).length">

                    <!-- ETH TOKENS -->
                    <div class="row col-12" v-for="current_coin in XEM">
                        <div class="row col-12" v-if="current_coin.maintenance_mode==false && current_coin.coin_id==9">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-2">
                                {{ current_coin.available }}
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input class="form-control" placeholder="Address" type="text"
                                           :id="'address_' + current_coin.coin_id" :value="current_coin.address"
                                           readonly/>
                                    <div class="">
                                        <button class="action-button"
                                                v-clipboard="current_coin.address"
                                                v-on:click="content_copied(current_coin.coin_id)"
                                                title="Copy Address">
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                        <button class="action-button" type="button" title="Withdraw" v-on:click="show_withdraw( current_coin )">
                                            <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                            <i class="fa fa-level-down" v-else></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!--Withdraw and error display-->
                            <div class="input-group col-sm-12" v-if="current_coin.show_withdraw">
                                <input class="form-control" placeholder="Address" type="text"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_address"><input class="form-control"
                                                                                     :placeholder="'Amount (Limit : ' +  [current_coin.daily_limit<0 ? 'Unlimited' : current_coin.daily_limit] + ')'"
                                                                                     type="number"
                                                                                     v-on:keyup="clear_error( current_coin )"
                                                                                     v-model="current_coin.payment_amount"><span
                                    class="input-group-btn"><button class="btn btn-withdraw" type="button"
                                                                    v-on:click="withdraw( current_coin, '1' )"
                                                                    :disabled="current_coin.btn_disabled">Withdraw >></button>
                  </span>
                            </div>
                            <div class="col-sm-12" v-if="current_coin.show_data == 0">
                                <br/>
                                <span class="text-muted">Amount: {{ current_coin.payment_amount }}</span><br>
                                <span class="text-warning">Fees: {{ current_coin.total_fee }}</span><br>
                                <span class="text-success">Creditable amount: <b>{{ current_coin.credit_amount }}</b></span><br>
                                <!--div style="text-align: right">
                                    <button class="btn btn-info" v-on:click="withdraw( current_coin, '2' )" :disabled="current_coin.btn_disabled" >Accept <i class="fa fa-circle-o-notch fa-spin" v-if="current_coin.btn_disabled"></i></button>
                                </div-->
                            </div>
                            <div class="col-sm-12" :class="current_coin.class" v-if="current_coin.error">{{
                                current_coin.error }}
                            </div>
                            <!--Withdraw and error display Ends-->
                        </div>
                        <div class="row col-12 clearfix" v-else-if="current_coin.maintenance_mode==false">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-2">
                                {{ current_coin.available }}
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">{{ current_coin.address }}</span>
                            </div>
                            <div class="col-sm-2">
                            <span class="float-right">
                            <div class="input-group">
                            <div class="">
                                <button class="action-button" type="button" title="Withdraw" v-on:click="show_withdraw( current_coin )">
                                    <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                    <i class="fa fa-level-down" v-else></i>
                                </button>
                            </div>
                            </div>
                            </span>
                            </div>
                            <!--Withdraw and error display-->
                            <div class="input-group col-sm-12" v-if="current_coin.show_withdraw">
                                <input class="form-control" placeholder="Address" type="text"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_address"><input class="form-control"
                                                                                     :placeholder="'Amount (Limit : ' +  [current_coin.daily_limit<0 ? 'Unlimited' : current_coin.daily_limit] + ')'"
                                                                                     type="number"
                                                                                     v-on:keyup="clear_error( current_coin )"
                                                                                     v-model="current_coin.payment_amount"><span
                                    class="input-group-btn"><button class="btn btn-withdraw" type="button"
                                                                    v-on:click="withdraw( current_coin, '1' )"
                                                                    :disabled="current_coin.btn_disabled">Withdraw >></button>
                  </span>
                            </div>
                            <div class="col-sm-12" v-if="current_coin.show_data == 0">
                                <br/>
                                <span class="text-muted">Amount: {{ current_coin.payment_amount }}</span><br>
                                <span class="text-warning">Fees: {{ current_coin.total_fee }}</span><br>
                                <span class="text-success">Creditable amount: <b>{{ current_coin.credit_amount }}</b></span><br>
                                <!--div style="text-align: right">
                                    <button class="btn btn-info" v-on:click="withdraw( current_coin, '2' )" :disabled="current_coin.btn_disabled" >Accept <i class="fa fa-circle-o-notch fa-spin" v-if="current_coin.btn_disabled"></i></button>
                                </div-->
                            </div>
                            <div class="col-sm-12" :class="current_coin.class" v-if="current_coin.error">{{
                                current_coin.error }}
                            </div>
                            <!--Withdraw and error display Ends-->
                        </div>
                        <div class="row col-12" v-if="current_coin.maintenance_mode==true">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-10 text-warning">Wallet under Maintenance</div>
                        </div>
                    </div>
                </div>

                <div class="row bg-white m-2 p-2 coin" v-for="wallet in wallets">

                    <!-- Group TOKENS -->
                    <div class="row col-12" v-for="current_coin in wallet">
                        <div class="row col-12" v-if="current_coin.maintenance_mode==false">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-2">
                                {{ current_coin.available }}
                            </div>
                            <div class="col-sm-8" v-if="(current_coin.coin_id!=13 && current_coin.coin_id!=136)">
                                <!-- if NOT ETN OR SUMO, TODO: PAIR MONERO COINS-->
                                <div class="input-group">
                                    <input class="form-control" placeholder="Address" type="text"
                                           :id="'address_' + current_coin.coin_id" :value="current_coin.address"
                                           readonly/>
                                    <div class="">
                                        <button class="action-button" v-clipboard="current_coin.address" v-on:click="content_copied(current_coin.coin_id)" title="Copy Address">
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                        <button class="action-button" type="button" title="Withdraw" v-on:click="show_withdraw( current_coin )">
                                            <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                            <i class="fa fa-level-down" v-else></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8" v-else> <!-- if ETN -->
                                <div class="input-group">
                                    <input id="etn_integrated_address" placeholder="Address" type="text"
                                           class="form-control" :value="splitAddress(current_coin.address).address"
                                           readonly/>
                                    <div class="">
                                        <button class="action-button" v-clipboard="splitAddress(current_coin.address).address" v-on:click="etn_content_copied('etn_integrated_address')" title="Copy Address">
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                        <button class="action-button" type="button" title="Details" v-on:click="show_details(current_coin)">
                                            <i class="fa fa-info"></i>
                                        </button>
                                        <button class="action-button" type="button" title="Withdraw" v-on:click="show_withdraw( current_coin )">
                                            <i class="fa fa-level-up" v-if="current_coin.show_withdraw"></i>
                                            <i class="fa fa-level-down" v-else></i>
                                        </button>
                                    </div>
                                </div>
                            </div> <!-- if ETN -->
                            <!--Withdraw and error display-->
                            <div class="input-group col-md-12" v-if="current_coin.show_details">
                                <label for="etn_wallet_address">Address</label>
                                <div class="input-group">
                                    <input id="etn_wallet_address" placeholder="Address" type="text"
                                           class="form-control"
                                           :value="splitAddress(current_coin.address).wallet_address" readonly/>
                                    <div class="">
                                        <button class="action-button" v-clipboard="splitAddress(current_coin.address).wallet_address" v-on:click="etn_content_copied('etn_wallet_address')" title="Copy Address">
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <label for="etn_payment_id">Payment ID</label>
                                <div class="input-group">
                                    <input id="etn_payment_id" placeholder="Address" type="text" class="form-control"
                                           :value="splitAddress(current_coin.address).payment_id" readonly/>
                                    <div class="">
                                        <button class="action-button" v-clipboard="splitAddress(current_coin.address).payment_id" v-on:click="etn_content_copied('etn_payment_id')" title="Copy Address">
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div> <!-- if ETN -->
                            <div class="input-group col-sm-12" v-if="current_coin.show_withdraw">
                                <input class="form-control" placeholder="Address" type="text"
                                       v-on:keyup="clear_error( current_coin )"
                                       v-model="current_coin.payment_address"><input class="form-control"
                                                                                     :placeholder="'Amount (Limit : ' +  [current_coin.daily_limit<0 ? 'Unlimited' : current_coin.daily_limit] + ')'"
                                                                                     type="number"
                                                                                     v-on:keyup="clear_error( current_coin )"
                                                                                     v-model="current_coin.payment_amount"><span
                                    class="input-group-btn"><button class="btn btn-withdraw" type="button"
                                                                    v-on:click="withdraw( current_coin, '1' )"
                                                                    :disabled="current_coin.btn_disabled">Withdraw >></button>
                  </span>
                            </div>
                            <div class="col-sm-12" v-if="current_coin.show_data == 0">
                                <br/>
                                <span class="text-muted">Amount: {{ current_coin.payment_amount }}</span><br>
                                <span class="text-warning">Fees: {{ current_coin.total_fee }}</span><br>
                                <span class="text-success">Creditable amount: <b>{{ current_coin.credit_amount }}</b></span><br>
                                <!--div style="text-align: right">
                                    <button class="btn btn-info" v-on:click="withdraw( current_coin, '2' )" :disabled="current_coin.btn_disabled" >Accept <i class="fa fa-circle-o-notch fa-spin" v-if="current_coin.btn_disabled"></i></button>
                                </div-->
                            </div>
                            <div class="col-sm-12" :class="current_coin.class" v-if="current_coin.error">{{
                                current_coin.error }}
                            </div>
                            <!--Withdraw and error display Ends-->
                        </div>
                        <div class="row col-12" v-if="current_coin.maintenance_mode==true">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/' + current_coin.symbol + '.png'"
                                     onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                {{ current_coin.symbol }}
                            </div>
                            <div class="col-sm-10 text-warning">Wallet under Maintenance</div>
                        </div>
                    </div>
                </div>

                <!-- FIAT MONEY USD -->
                <div class="row bg-white m-2 p-2 coin" v-if="Object.keys(USD).length">
                    <div class="row col-12">
                        <div class="row col-12" v-if="USD.maintenance_mode==false">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/USD.png'" onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                USD
                            </div>
                            <div class="col-sm-2">
                                {{ USD.available }}
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">{{ USD.address }}</span>
                            </div>
                            <div class="col-sm-2">
                    <span class="float-right">
                    <div class="input-group">
                        <div class="action-button-group">
                            <button class="action-button" type="button" title="Deposit" v-on:click="show_deposit(USD)">
                                <i class="fa fa-level-up"></i>
                            </button>
                            <button class="action-button" type="button" title="Withdraw" v-on:click="" :disabled="true">
                                <i class="fa fa-level-down"></i>
                            </button>
                        </div>
                    </div>
                    </span>
                            </div>
                            <div class="col-sm-12" v-if="USD.show_deposit">
                                <div class="input-group">
                                    <input class="form-control mr-3" placeholder="Enter Amount.. (minimum $50)"
                                           type="number" v-on:keyup="USD.error=''" v-model="USD.payment_amount">
                                    <div class="input-group-btn">
                                        <button class="pay-button btn" id="payPal" type="button"
                                                v-on:click="paypal(USD)" :disabled="USD.btn_disabled">
                                            <img src="/img/Paypal-logo.png"/>
                                            <span v-if="USD.btn_disabled==false"> >> </span>
                                            <i v-if="USD.btn_disabled" class="fa fa-circle-o-notch fa-spin"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 text-danger" v-if="USD.error != ''">{{ USD.error }}</div>
                        </div>
                        <div class="row col-12" v-if="USD.maintenance_mode==true">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/USD.png'" onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                USD
                            </div>
                            <div class="col-sm-10 text-warning">Wallet under Maintenance</div>
                        </div>
                    </div>
                </div>

                <!-- FIAT MONEY EUR -->
                <div class="row bg-white m-2 p-2 coin" v-if="Object.keys(EUR).length">
                    <div class="row col-12">
                        <div class="row col-12" v-if="EUR.maintenance_mode==false">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/EUR.png'" onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                EUR
                            </div>
                            <div class="col-sm-2">
                                {{ EUR.available }}
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">{{ EUR.address }}</span>
                            </div>
                            <div class="col-sm-2">
                    <span class="float-right">
                    <div class="input-group">
                        <div class="">
                            <button class="action-button" type="button" title="Deposit" v-on:click="show_deposit(EUR)">
                                <i class="fa fa-level-up"></i>
                            </button>
                            <button class="action-button" type="button" title="Withdraw" v-on:click="" :disabled="true">
                                <i class="fa fa-level-down"></i>
                            </button>
                        </div>
                    </div>
                    </span>
                            </div>
                            <!-- <div class="col-sm-12" id="pay-block"> -->
                            <div class="col-sm-12" id="pay-block" v-if="EUR.show_deposit">
                                <div class="input-group">
                                    <input class="form-control mr-3" placeholder="Enter Amount.. (minimum €50)"
                                           type="number" v-on:keyup="EUR.error=''" v-model="EUR.payment_amount">
                                    <span class="input-group-btn">
                            <button class="pay-button btn" id="payPal" type="button" v-on:click="paypal(EUR, user_id)"
                                    :disabled="EUR.btn_disabled"><div><img src="/img/Paypal-logo.png" class="mx-3"><span
                                    v-if="EUR.btn_disabled==false"> >> </span><i v-if="EUR.btn_disabled"
                                                                                 class="fa fa-circle-o-notch fa-spin"></i></div></button>
                            <button class="pay-button btn" id="bankTransfer" type="button"
                                    v-on:click="bankTransfer(EUR, user_id)" :disabled="EUR.btn_disabled">
                                <i class="fa fa-credit-card"></i>
                                <span v-if="EUR.btn_disabled==false"></span>
                                <i v-if="EUR.btn_disabled" class="fa fa-circle-o-notch fa-spin"></i>Bankwire >></button>
                        </span>
                                </div>
                            </div>
                            <!-- <div class="col-sm-12 text-danger" v-if="EUR.error">{{ EUR.error }}</div> -->
                        </div>
                        <div class="row col-12" v-if="EUR.maintenance_mode==true">
                            <div class="col-sm-1">
                                <img v-bind:src="'/img/coin/32/EUR.png'" onerror="this.src='/img/coin/32/noimage.png'">
                            </div>
                            <div class="col-sm-1">
                                EUR
                            </div>
                            <div class="col-sm-10 text-warning">Wallet under Maintenance</div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ADD WALLETS -->
            <!-- <div class="container" v-if="add_wallet===1">
                <div class="container-fluid m-2 text-center text-danger"><h3><i class="fa fa-times"
                                                                                style="cursor: pointer"
                                                                                title="Show Wallet"
                                                                                v-on:click="switch_page(2)"></i></h3>
                </div>
                <div class="row bg-white m-2 p-2">
                    <div class="col-sm-6">
                        <v-select class="wallet__select" v-model="selected_coin" label="coin_title" :options="coins">
                            <template slot="option" slot-scope="option">
                                <img class="wallet__select__image" :src="'/img/coin/32/'+option.symbol+'.png'" onerror="this.src='/img/coin/32/noimage.png'">
                                <span class="wallet__select__text">{{ option.coin_title }}</span>
                            </template>
                        </v-select>
                    </div>
                    <div class="col-sm-6">
                        <div class="spinner-wrapper">
                            <div class="row" v-if="selected_coin!=''">
                                <div class="col-sm-2"><img v-bind:src="'/img/coin/32/'+selected_coin.symbol+'.png'"
                                                           onerror="this.src='/img/coin/32/noimage.png'"></div>
                                <div class="col-sm-7">{{ selected_coin.coin_title }}</div>
                                <div class="col-sm-3">
                                    <label class="switch">
                                        <input v-model="selected_option" type="checkbox"
                                               v-bind:checked="selected_option">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="loading" v-if="add_wallet_spinner"></div>
                            <div class="spinner-bg" v-if="add_wallet_spinner"></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="loading" v-if="show_wallet_spinner"></div>
            <div class="spinner-bg" v-if="show_wallet_spinner"></div>
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payment</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card credit-card-box">
                                    <div class="card-header">
                                        <div class="row display-tr">
                                            <h3 class=" display-td">Payment Details</h3>
                                            <div class="display-td">
                                                <img class="img-responsive pull-right"
                                                     src="http://i76.imgup.net/accepted_c22e0.png">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="alert alert-danger error" v-if="errorflag">
                                            <ul id="">
                                                <li v-for="msg in message">{{ msg }}</li>
                                            </ul>
                                        </div>
                                        <div class="alert alert-success error" v-if="successflag">
                                            <p>Transcation have done successfully. you transction id is: <br/> <strong>{{txnid}}</strong>
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">First Name</label>
                                            <div class="col-md-12">
                                                <input type="text" name="first_name"
                                                       v-validate="'required|alpha_spaces'"
                                                       v-model="creditcard.first_name" class="form-control input-md"
                                                       autofocus autocomplete="off">
                                                <span v-show="errors.has('first_name')" class="help-block">{{ errors.first('first_name') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Last Name</label>
                                            <div class="col-md-12">
                                                <input type="text" name="last_name" v-validate="'required|alpha_spaces'"
                                                       v-model="creditcard.last_name" class="form-control input-md"
                                                       autofocus autocomplete="off">
                                                <span v-show="errors.has('last_name')" class="help-block">{{ errors.first('last_name') }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Card Number</label>
                                            <div class="col-md-12">
                                                <input type="text" name="cardnumber"
                                                       v-validate="'required|numeric|credit_card'"
                                                       v-model="creditcard.cardnumber" class="form-control input-md"
                                                       autofocus autocomplete="off">
                                                <span v-show="errors.has('cardnumber')" class="help-block">{{ errors.first('cardnumber') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12  control-label">Card Expiry Month</label>
                                            <div class="col-sm-12">
                                                <select class="form-control input-md" autofocus name="cc_exp_mo"
                                                        v-model="creditcard.exp_month" v-validate
                                                        data-vv-rule="required">
                                                    <option value="">Month</option>
                                                    <option value="01">January</option>
                                                    <option value="02">February</option>
                                                    <option value="03">March</option>
                                                    <option value="04">April</option>
                                                    <option value="05">May</option>
                                                    <option value="06">June</option>
                                                    <option value="07">July</option>
                                                    <option value="08">August</option>
                                                    <option value="09">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class=" col-md-12  control-label">Card Expiry Year</label>
                                            <div class="col-sm-12">
                                                <select class="form-control input-md" name="cc_exp_yr" autofocus
                                                        v-model="creditcard.exp_year" v-validate
                                                        data-vv-rule="required">
                                                    <option value="">Year</option>
                                                    <option v-for="event in events" :value="event">{{event}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Card CVV</label>
                                            <div class="col-md-12">
                                                <input type="text" name="cvv" v-validate="'required|numeric'" autofocus
                                                       v-model="creditcard.cvv" autocomplete="off"
                                                       class="form-control input-md"
                                                       title="Three digits at back of your card">
                                                <span v-show="errors.has('cvv')" class="help-block">{{ errors.first('cvv') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label">Amount</label>
                                            <div class="col-md-8">
                                                <input type="text" name="amount" v-validate="'required|numeric'"
                                                       autofocus v-model="creditcard.available" autocomplete="off"
                                                       class="form-control input-md">
                                                <span v-show="errors.has('amount')" class="help-block">{{ errors.first('amount') }}</span>
                                            </div>
                                        </div>
                                        <div class="btn-block pull-right" style="width: auto">
                                            <input type="hidden" name='cr' id='cr'>
                                            <button class="btn btn-danger" v-on:click="hide">Cancel</button>
                                            <button class="action-button" v-on:click="process_credit_card">&nbsp;&nbsp;Pay&nbsp;&nbsp;</button>
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
</template>

<script>
    import Config from "../config";

    export default {
        name: "wallet",

        data() {
            return {
                add_wallet: 0,
                user_id: "",

                wallets: {},
                erc_20: {},

                coins: [],
                selected_coin: "Select coin:",
                add_wallet_spinner: false,
                show_wallet_spinner: false,
                selected_option: false,
                selected_wallet: {
                    wallet_id: 0,
                    wallet_enabled: 0
                },

                BTC: {},
                ETH: {},
                USD: {},
                EUR: {},
                XEM: {},
                GENERAL: {},
                current_coin: "",

                flash: [],
                error: "",
                valid: "",
                formErrors: {},

                message: {},
                successMsg: "",
                txnid: "",
                events: [],
                currency: "",

                creditcard: {
                    first_name: "",
                    last_name: "",
                    cardnumber: "",
                    exp_month: "",
                    exp_year: "",
                    cvv: "",
                    currency: "",
                    amount: 0,
                    coin_id: 0
                },

                errorflag: false,
                successflag: false,
                validate: 0,

                is_generating: 0,
                toUpdateWallets: []
            };
        },

        created() {
            /**
             * Echo must be authenticated and joined channel
             * as soon as possible before init wallet
             * actually gets called, so notifications
             * reach user
             */
            window.Echo.private("App.Models.User." + Config.user.user_id).notification(
                notification => {
                    /**
                     * Api response may be slower than
                     * sockets, so we push wallets
                     * so then after request we
                     * apply changes
                     */
                    const wallet = notification.data;
                    if (this.show_wallet_spinner) {
                        return this.toUpdateWallets.push(wallet);
                    }
                    if (this[wallet.market] && this[wallet.market][wallet.coin_id]) {
                        this[wallet.market][wallet.coin_id].available = wallet.amount;
                    } else if (
                        this.wallets[wallet.market] &&
                        this.wallets[wallet.market][wallet.coin_id]
                    ) {
                        this.wallets[wallet.market][wallet.coin_id].available = wallet.amount;
                    }
                }
            );
        },

        watch: {
            show_wallet_spinner: function (spinning) {
                if (!spinning) {
                    this.toUpdateWallets.map(wallet => {
                        if (this[wallet.market] && this[wallet.market][wallet.coin_id]) {
                            this[wallet.market][wallet.coin_id].available = wallet.amount;
                        } else if (
                            this.wallets[wallet.market] &&
                            this.wallets[wallet.market][wallet.coin_id]
                        ) {
                            this.wallets[wallet.market][wallet.coin_id].available =
                                wallet.amount;
                        }
                    });
                    this.toUpdateWallets = [];
                    this.$forceUpdate();
                }
            },

            selected_coin: function () {
                if (this.selected_coin === null) {
                    this.selected_coin = "Select coin:";
                    this.selected_wallet = {
                        wallet_id: 0,
                        wallet_enabled: 0
                    };
                }

                if (this.selected_coin != "Select coin:") {
                    this.showCoin();
                }
            },

            selected_option: function () {
                this.create_or_update_wallet();
                this.init_wallets();
            }
        },

        mounted: function () {
            this.show_wallet_spinner = true;
            //Load only after full page is rendered
            this.$nextTick(function () {
                //Give a bit more time echo to authenticate and join channel

                setTimeout(() => {
                    this.loadCoins();
                    this.init_wallets();
                }, 100);
                this.cardmonths();
            });

            if (typeof this.$route.params.info !== "undefined") {
                this.withdraw_confirm(this.$route.params.info);
            }
        },

        methods: {
            withdraw_confirm: function (info) {
                $.ajax({
                    type: "POST",
                    url: "/api/crypto/withdrawconfirm/" + info,
                    cache: false,
                    data: {ajaxRequest: true},
                    success: function (data, status, error) {
                        if (data.status == "show_data") {
                            this.$swal({
                                title: "Withdraw Confirm Not Success",
                                text: data.message,
                                type: "warning",
                                cancelButtonText: "Ok Got It !",
                                showCancelButton: true,
                                showConfirmButton: false
                            }).catch(this.$swal.noop);
                        } else if (!data.status) {
                            this.$swal({
                                title: "Withdraw Confirm Status Not Success",
                                text: data.message,
                                type: "warning",
                                cancelButtonText: "Ok Got It !",
                                showCancelButton: true,
                                showConfirmButton: false
                            }).catch(this.$swal.noop);
                        } else {
                            this.$swal({
                                title: "Withdraw successfully completed",
                                text: data.message,
                                type: "info",
                                cancelButtonText: "Ok Got It !",
                                showCancelButton: true,
                                showConfirmButton: false
                            }).catch(this.$swal.noop);
                        }
                    }.bind(this),
                    error: function (data, status, error) {
                    }
                });
            },

            switch_page: function (page) {
                this.add_wallet = page === 1 ? 1 : 0;

                if (this.add_wallet == 1) this.loadCoins();

                if (this.add_wallet == 0) this.init_wallets();

                this.selected_coin = "Select coin:";

                this.selected_wallet = {
                    wallet_id: 0,
                    wallet_enabled: 0
                };
            },

            init_wallets: function () {
                this.show_wallet_spinner = true;

                $.ajax({
                    type: "GET",
                    url: "/api/init-wallet",
                    async: true,
                    cache: false,
                    success: (response, status, error) => {
                        this.wallets = response;
                        this.user_id = this.wallets.length ? this.wallets[0].user_id : "";

                        Vue.set(this, "BTC", this.wallets.BTC ? this.wallets.BTC : {});
                        Vue.set(this, "ETH", this.wallets.ETH ? this.wallets.ETH : {});
                        Vue.set(this, "XEM", this.wallets.XEM ? this.wallets.XEM : {});
                        Vue.set(
                            this,
                            "GENERAL",
                            this.wallets.general_market ? this.wallets.general_market : []
                        );
                        Vue.set(this, "USD", this.wallets.USD ? this.wallets.USD[10] : {});
                        Vue.set(this, "EUR", this.wallets.EUR ? this.wallets.EUR[11] : {});

                        Vue.delete(this.wallets, "general_market");
                        Vue.delete(this.wallets, "BTC");
                        Vue.delete(this.wallets, "ETH");
                        Vue.delete(this.wallets, "XEM");
                        Vue.delete(this.wallets, "USD");
                        Vue.delete(this.wallets, "EUR");

                        this.show_wallet_spinner = false;
                    },

                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            loadCoins: function () {
                this.show_wallet_spinner = true;
                $.ajax({
                    type: "GET",
                    url: "/fetch-coins",
                    success: (response, status, error) => {
                        this.coins = response;

                        // this.show_wallet_spinner = false;
                    },
                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            showCoin: function () {
                $.ajax({
                    type: "GET",
                    url: "/show-coin",
                    async: false,
                    cache: false,
                    data: {coin_id: this.selected_coin.coin_id},
                    success: (response, status, error) => {
                        Vue.set(this, "selected_wallet", response);

                        this.selected_option =
                            this.selected_wallet.wallet_enabled == 0 ? false : true;
                    },
                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            create_or_update_wallet: function () {
                $.ajax({
                    type: "POST",
                    url: "/create-update-wallet",
                    async: false,
                    cache: false,
                    data: {
                        wallet_id: this.selected_wallet.wallet_id,
                        coin_id: this.selected_coin.coin_id,
                        wallet_enabled: this.selected_option ? 1 : 0,
                        symbol: this.selected_coin.symbol
                    },
                    success: (response, status, error) => {
                        Vue.set(this, "selected_wallet", response);
                    },
                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            content_copied: function (id) {
                $("#address_" + id).css("background-color", "#60eff7");
                setTimeout(function () {
                    $("#address_" + id).css("background-color", "#fff");
                }, 100);
            },

            etn_content_copied: function (id) {
                $("#" + id).css("background-color", "#60eff7");
                setTimeout(function () {
                    $("#" + id).css("background-color", "#fff");
                }, 100);
            },

            splitAddress: function (address) {
                var splited_address = address.split("::");

                return {
                    wallet_address: splited_address[0],
                    address: splited_address[1],
                    payment_id:
                    splited_address[2] +
                    "000000000000000000000000000000000000000000000000"
                };
            },

            show_deposit: function (coin) {
                coin.payment_amount = null;
                coin.show_deposit = !coin.show_deposit;
            },
            bankTransfer: function (coin, user_id) {
                coin.error = "";
                $(".error").remove();
                $(".payment-message").remove();
                $("#payPal").removeClass("active");
                $("#bankTransfer").addClass("active");
                if (coin.payment_amount === 0 || $.trim(coin.payment_amount) === "") {
                    coin.error = "Please fill amount to pay";
                    $("#pay-block").append(
                        `<div class="col-sm-12 text-danger error wallet" v-if=${coin.error}>${
                            coin.error
                            }</div>`
                    );
                    return false;
                }

                if (coin.payment_amount < 50) {
                    coin.error = "Minimum deposit amount is 50";
                    $("#pay-block").append(
                        `<div class="col-sm-12 text-danger error wallet" v-if=${coin.error}>${
                            coin.error
                            }</div>`
                    );
                    return false;
                }
                $("#pay-block").append(
                    `<p class="payment-message text-danger" id="wallet">Please pay the amount of ${
                        coin.symbol
                        }. ${
                        coin.payment_amount
                        } to IBAN NL90BUNQ2205979914 on name of Opentrader N.V. i.o. under subject ${ coin.address }</p>`
                );
            },
            paypal: function (coin) {
                $(".payment-message").remove();
                $("#payPal").addClass("active");
                $("#bankTransfer").removeClass("active");

                if (coin.payment_amount == 0 || $.trim(coin.payment_amount) == "") {
                    coin.error = "Please fill amount to pay";
                    return false;
                }

                if (coin.payment_amount < 10) {
                    coin.error = "Minimum deposit amount is 10";
                    return false;
                }

                coin.btn_disabled = true;

                let payment_details = {
                    amount: coin.payment_amount,
                    currency: coin.symbol,
                    coin_id: coin.coin_id
                };

                $.ajax({
                    type: "POST",
                    url: "/paypal",
                    data: payment_details,
                    async: true,
                    cache: false,
                    success: (response, status, error) => {
                        if (response.success == 1) {
                            window.location.href = response.paypal_url;
                        }
                    },

                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            show_withdraw: function (coin) {
                this.clear_error(coin);
                coin.payment_amount = null;
                coin.payment_address = null;
                coin.show_withdraw = !coin.show_withdraw;
                coin.btn_disabled = false;
            },

            show_details: function (coin) {
                coin.show_details = !coin.show_details;
            },

            cardmonths() {
                var d = new Date();
                var curretyear = d.getFullYear();
                var years = new Date(curretyear + 10);
                var i;
                for (i = curretyear; i <= years; i++) {
                    this.events.push(i);
                }
            },

            withdraw: function (coin, btn) {
                if ($.trim(coin.payment_address) == "") {
                    coin.error = "Please enter a valid address to withdraw";
                    coin.class = "text-danger";
                    return false;
                }

                switch ($.trim(coin.payment_amount)) {
                    case "":
                    case "0":
                        coin.class = "text-danger";
                        coin.error = "Please enter a valid amount to withdraw";
                        return false;

                    default:
                        if (coin.daily_limit != -1) {
                            if (
                                parseFloat(coin.payment_amount) > parseFloat(coin.daily_limit)
                            ) {
                                coin.class = "text-danger";
                                coin.error =
                                    "You can't withdraw an amount greater than " +
                                    parseFloat(coin.daily_limit).toString();
                                return false;
                            }
                        }
                }

                if (btn == 1) {
                    coin.show_data = 1;
                }

                coin.btn_disabled = true;

                $.ajax({
                    type: "POST",
                    url: "/api/crypto/withdraw",
                    cache: false,
                    data: {
                        ajaxRequest: true,
                        toaddress: coin.payment_address,
                        amount: coin.payment_amount,
                        coinid: coin.coin_id,
                        show_data: coin.show_data
                    },
                    success: function (data, status, error) {
                        coin.btn_disabled = false;
                        if (data.status == "show_data") {
                            coin.show_data = 0;
                            coin.total_fee = data.total_fee;
                            coin.credit_amount = data.credit_amount;

                            coin.btn_disabled = !data.message ? false : true;
                            coin.class = "text-danger";
                            coin.error = data.message;
                        } else if (!data.status) {
                            coin.class = "text-danger";
                            coin.error = data.message;
                        } else {
                            coin.show_data = 1;
                            coin.total_fee = 0;
                            coin.credit_amount = 0;
                            this.clear_error(coin);

                            coin.class = "text-success";
                            coin.error = "Transaction successfully completed";
                        }
                    }.bind(this),
                    error: function (data, status, error) {
                    }
                });
            },

            clear_error: function (coin) {
                coin.class = "";
                coin.error = "";
                coin.show_data = 1;
                coin.btn_disabled = false;
            },

            hide() {
                this.creditcard = {
                    fullname: "",
                    cardnumber: "",
                    exp_date: "",
                    exp_year: "",
                    cvv: "",
                    currency: "",
                    amount: ""
                };

                $("#myModal").modal("hide");
                $("body")
                    .find(".modal-backdrop")
                    .removeClass("show")
                    .addClass("hide");
                $("#myModal")
                    .removeClass("show")
                    .addClass("hide");
            },

            credit_card: function (payment_details) {
                $("#myModal").modal("show");
                $("body")
                    .find(".modal-backdrop")
                    .removeClass("hide")
                    .addClass("show");
                $("#myModal")
                    .removeClass("hide")
                    .addClass("show");

                this.creditcard.currency = payment_details.currency;
                this.creditcard.available = payment_details.available;
                this.creditcard.coin_id = payment_details.coin_id;
            },

            process_credit_card: function () {
                $.ajax({
                    type: "POST",
                    url: "/creditcard-paypal",
                    data: this.creditcard,
                    async: false,
                    cache: false,
                    success: (response, status, error) => {
                        this.errorflag = false;
                        this.successflag = false;

                        if (response.success == -1) {
                            this.errorflag = true;
                            this.message = response.data;
                        } else if (response.success == 0) {
                            this.errorflag = true;
                            this.message = {
                                error:
                                    "We are not able to process your request this time, please try again later"
                            };
                        } else {
                            this.successflag = true;
                            this.txnid = response.data.TRANSACTIONID;
                        }
                    },

                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            }
        }
    };
</script>
<style type="text/scss" lang="scss">
    .wallet__select {
        width: 100%;
        ul {
            margin-top: 0 !important;
            max-height: 300px !important;
            .wallet__select__image {
                height: 20px;
                width: 20px;
                margin-right: 15px;
            }
            .wallet__select__text {
                line-height: 20px;
            }
        }

        div > input {
            width: 0px !important;
        }
    }

    .table__title {
        background-color: #f9f9fd;
        min-height: 64px;
    }

    .table__title > div {
        display: flex;
        align-items: center;
    }

    .table__title > div:first-child {
        font-weight: 500;
        color: #0a0a0a;
        font-size: 120%;
    }

    .table__title__btn {
        background: transparent;

        border-radius: 6px;
        width: 110px;
        height: 42px;
        line-height: normal;
        transition: 300ms;
    }

    .table__title__btn-grey {
        border: 2px solid #d9e5e8 !important;
        color: #383d41 !important;
    }

    .table__title__btn-grey:hover {
        background: rgb(225, 233, 238);
    }

    .table__title__btn-red {
        color: #721c24 !important;

        border: 2px solid #f5c6cb !important;
    }

    .table__title__btn-red:hover {
        background: #f8d7da;
    }

    .row {
        margin-left: 0;
        margin-right: 0;
    }

    #wallet {
        line-height: 52px;
    }

    #wallet img {
        width: 20px;
        vertical-align: middle;
    }

    #wallet .coin {
        // border: 1px solid #AAAAAA;
        border-bottom: 2px solid #dee2e6;
        padding: 10px 0 !important;
    }

    #wallet .coin:last-child {
        // border: 1px solid #AAAAAA;
        border-bottom: none;
    }

    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .payment-message {
        margin-bottom: 0 !important;
    }

    .fa-spin {
        position: absolute;
        left: 0;
        right: 0;
    }

    /* Hide default HTML checkbox */
    .switch input {
        display: none;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: 0.4s;
        transition: 0.4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: 0.4s;
        transition: 0.4s;
    }

    input:checked + .slider {
        background-color: #00cccc;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #00cccc;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .pay-button {

        border: 2px solid #b8daff;
        color: #004085;
        border-radius: 5px !important;
        //   padding: 0.5em 2.5em !important;
        //   padding: 0 !important;
        //   color: #747980 !important;

        font-family: helvetica;
        //   font-weight: 400;
        //   height: 48px;
        font-size: 14px;
        background: transparent !important;
        height: 42px;
        padding: 0;
        width: 110px;
        &:hover {
            background-color: #cce5ff !important;

        }
    }

    // .pay-button.active {
    //   border-color: #6bb437 !important;
    //   border-width: 2px;
    // }

    .pay-button i {
        font-size: 20px;
        //   color: #535b62;
    }

    .btn.focus:active,
    .btn:focus {
        box-shadow: none !important;
    }

    .pay-button img {
        height: 15px;
        width: 59px !important;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .action-button {
        font-size: 1.5em;
        border: 2px solid #bee5eb;
        color: #0c5460;
        background-color: #ffffff;
        font-weight: bold;
        cursor: pointer;
        min-height: 30px;
        width: 52.5px;
        line-height: 30px;
        border-radius: 5px;
        &:hover:not([disabled]) {
            background: #d1ecf1 !important;
        }

        &:disabled {
            border-color: #ffeeba;
            color: #856404;
            cursor: not-allowed;
        }
        &:first-child {
            margin-left: 15px;
        }
    }

    .input-group {
        .action-button {
            height: 40px;
        }
    }

    .action-button-group {
    }

    .btn-withdraw {
        color: #155724 !important;
        margin-left: 15px !important;
        background: transparent !important;
        border: 2px solid #c3e6cb;
        height: 42px;
        padding: 0;
        width: 110px;

        &:hover {
            background-color: #d4edda !important;
        }
    }

    .text-muted {
        padding: 0 12px;
        color: #e0e0fa !important;
    }

</style>

