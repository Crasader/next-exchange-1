<template>
	<div class="animated fadeIn">
		<div class="card">
			<div class="card-header">

				<div id="markets__title" class="pull-left card-title ">Markets</div>

				<div class="pull-right">
					<button type="button" class="btn btn--outline btn-primary" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
						{{ transaction_coin.coin_coin }}
					</button>
					<div class="drop-select dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="javascript:void(null)"
						   v-on:click="set_transaction_coin(trans_coin)"
						   v-for="trans_coin in transaction_coins">{{ trans_coin.coin_coin }}</a>
					</div>
				</div>

			</div>

			<div id="loader-div" class=""></div>
			<div id="spinner" class=""></div>

			<table class="table table-responsive-sm table-next" style="margin-bottom: 0;">
				<thead>
				<tr>
					<td></td>
					<td>Name</td>
					<td>Change&nbsp;&nbsp;</td>
					<td>Market</td>
					<td>Sell</td>
					<td>Buy</td>
					<td>High/Low</td>
					<!--<th>Action</th>-->
				</tr>
				</thead>
				<tbody v-for="(coincontent, index) in content">

				<tr>
					<td><img class='coin-icon' v-bind:src="'/img/coin/32/'+coincontent.symbol+'.png'"
							 onerror="this.src='/img/coin/32/noimage.png'"></td>
					<td>
						<router-link :to="'/coin-details/' +  coincontent.symbol + '/' + transaction_coin.coin_coin">
							<span style="color: rgb(102, 102, 102)">{{coincontent.coin_title }}</span></router-link>
						<!-- <router-link :to="'/coin-details/' +  coincontent.symbol + '/' + transaction_coin.coin_coin">
							<span class="infoIcon"><i class='fa fa-info-circle' title='Details'></i></span></router-link> -->

						<span class="infoIcon" @click="showAdditionalInfo(index)"><i class='fa fa-info-circle'
																					 title='Details'></i></span>
						<span>
								<router-link :to="'/orderbook/' + coincontent.symbol + '/BTC'" v-if="coincontent.symbol === 'ETH'"> {{coincontent.symbol}}</router-link>
								<router-link :to="'/orderbook/' + coincontent.symbol + '/ETH'"
											 v-else> {{coincontent.symbol}}</router-link>
							</span>
					</td>
					<td>
						<span v-if="parseFloat(coincontent.change) == 0"><i class="fa"
																			aria-hidden="true"></i>&nbsp;{{coincontent.change}}</span>
						<span class="text-success" v-else-if="parseFloat(coincontent.change) > 0.0000"><i
								class="fa fa-arrow-up" aria-hidden="true"></i>&nbsp;{{coincontent.change}}</span>
						<span class="text-danger" v-else><i class="fa fa-arrow-down"
															aria-hidden="true"></i>&nbsp;{{coincontent.change}}</span>
					</td>
					<td>{{coincontent.price}}</td>
					<td>
						<button v-bind:class="{'disabled': coincontent.wallet_enabled == 0}"
								class="btn btn--xs btn--outline btn-primary"
								:disabled="coincontent.wallet_enabled == 0"
								v-on:click="buysell_model(coincontent, 'SELL')">Sell
						</button>&nbsp;{{coincontent.sell}}
					</td>
					<td>
						<button v-bind:class="{'disabled': coincontent.wallet_enabled == 0}"
								class="btn btn--xs btn--outline btn-primary"
								:disabled="coincontent.wallet_enabled == 0"
								v-on:click="buysell_model(coincontent, 'BUY')">Buy
						</button>&nbsp;{{coincontent.buy}}
					</td>
					<td>{{coincontent.high}} / {{coincontent.low}}</td>
					<!--<td><a href="#" v-on:click=Edit(coincontent.coin_id)>Edit</a>&nbsp;&nbsp; | <a href="#" v-on:click=Delete(coincontent.coin_id)>Delete</a></td>-->
				</tr>
				<tr v-if="additionalInfoIndexesToShow.indexOf(index) > -1">
					<td colspan="7">
						<!-- <iframe :src="'/coin-details/' +  coincontent.symbol + '/' + transaction_coin.coin_coin" ></iframe> -->
						<CoinDetailsForMarketTable :coincontent='coincontent.symbol'
												   :transaction='transaction_coin.coin_coin'></CoinDetailsForMarketTable>
					</td>
				</tr>
				</tbody>
			</table>

			<div class="modal fade" id="update-Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel">Edit Item</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						</div>
						<div class="modal-body">
							<div class="dropimage" style="margin-top:100px;">
								<droply id="myDroply"
										ref="droplyOne"
										url="Storeimage"
										upload-message-text="Drop file(s) to upload <br><strong>or click</strong>"
										@droply-file-added="onFileAdded"
										@droply-removed-file="onFileRemoved"
										@droply-success="onSuccess">
								</droply>
								<button v-if="showRemoveAllButton" class="btn btn-primary" @click="removeAll()">
									Remove all
								</button>
								<div class="error" v-if="errorflag=true">
									<ul id="">
										<li v-for="msg in fileerror">{{ msg }}</li>
									</ul>
								</div>
							</div>
							<br>
							<span class="alert alert-danger">Please Upload Only png image and Coin title will be image Name</span>
							<br>
							<br>
							<!-- <div class="row">
                                <div class="col-sm-8">
                                <div class="alert alert-danger" v-for="formErrors(index,value) in error">
                                    <strong>{{value}}!</strong>
                                </div>
                                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="Coinupdate()">
                                    <div class="form-group">
                                        <label for="title">Coin  title:</label>
                                        <input type="text" name="title" class="form-control" v-model="coin.title" />
                                        <span v-if="formErrors.title" class="error text-danger">{{ formErrors.title[0] }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="title"> Desciption:</label>
                                        <input type="text" name="description" class="form-control" v-model="coin.description" />
                                        <span v-if="formErrors.description" class="error text-danger">{{ formErrors.description[0] }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="title"> Market:</label>
                                        <input type="text" name="market" class="form-control" v-model="coin.market" />
                                        <span v-if="formErrors.market" class="error text-danger">{{ formErrors.market[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div> -->
						</div>
					</div>
				</div>
			</div>

			<!-- Modal BUY/SELL -->
			<div class="modal fade" id="buy_sell_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header modal-title background-modal-title">
							<div class="">
								<div class="box-left">{{ trans_type }}</div>
								<br />
								<div class="box-right"><i>Available {{ coin_available }}</i>: <strong>{{ wallet_balance
									}}</strong></div>
							</div>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<transition name="fade" v-if="orderSuccess">
							<div class="alert alert-success" role="alert" style="text-align: center">
								<strong>Your order for <span class="text-lowercase">{{ trans_type }}ing</span>
									{{ selected_coin.symbol }}@{{ order_price }} {{ transaction_coin.coin_coin
									}} is placed.</strong>
							</div>
						</transition>
						<div class="modal-body">
							<div class="card-block">
								<div class="form-group row">
									<label for="inputAmount" class="col-sm-3 col-form-label">Amount</label>
									<div class="col-sm-9">
                                        <div class="input-group coin-amount-input">
											<input class="form-control form-control-lg" type="number"
												   placeholder="Amount" v-model="order_amount">
											<span class="input-group-btn">
												<button class="btn" type="button">{{ selected_coin.symbol
													}}</button>
											</span>
										</div>
										<span class="text-danger"
											  v-if="trans_errors.order_amount"> {{ trans_errors.order_amount }} </span>
									</div>
								</div>
								<div class="form-group row">
									<label for="inputPrice" class="col-sm-3 col-form-label">Price</label>

									<div class="col-sm-9">
                                        <div class="input-group coin-amount-input">
											<input class="form-control form-control-lg" type="number"
												   placeholder="Price" v-model="order_price">
											<div class="input-group-btn">
                                                <button type="button" class="btn">
													{{ transaction_coin.coin_coin }}
												</button>
											</div>
										</div>
										<span class="text-danger"
											  v-if="trans_errors.order_price"> {{ trans_errors.order_price }} </span>
									</div>
								</div>
								<div class="form-group row">
									<label for="inputTotal" class="col-sm-3 col-form-label">Total</label>
									<div class="col-sm-9">
                                        <div class="input-group coin-amount-input">
											<input class="form-control form-control-lg" type="number"
												   placeholder="Total" readonly v-model="order_total">
											<span class="input-group-btn">
												<button class="btn"
                                                        type="button"> {{ transaction_coin.coin_coin }} </button>
											</span>
										</div>
										<span class="text-danger"
											  v-if="trans_errors.order_total"> {{ trans_errors.order_total }} </span>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
                            <button type="button" class="order-action-button dark" data-dismiss="modal">Close</button>
                            <button type="button" class="order-action-button" v-on:click="save_order"
                                    :disabled="price_negative">{{ trans_type }}
                            </button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
	@media (max-width: 800px) {
		td:nth-child(4),
		td:last-child {
			display: none;
		}

		.infoIcon {
			display: none;
		}
	}

	#markets__title {
		top: 8px;
	}

	.infoIcon {
		cursor: pointer;
	}

	.text-red {
		color: red;
	}


</style>


<script>
    import Droply from "droply";

    const numeral = require("numeral");
    let math = require("mathjs");

    import CoinDetailsForMarketTable from "./CoinDetailsForMarketTable.vue";

    export default {
        name: "markets",
        components: {
            Droply,
            CoinDetailsForMarketTable
        },
        data() {
            return {
                content: [],
                formErrors: {},
                error: false,
                processQueue: false,
                showRemoveAllButton: false,
                fileerror: {},
                errorflag: false,
                messages: {},
                coin: {
                    title: "",
                    description: "",
                    mrket: "",
                    id: ""
                },
                val: "",

                selected_coin: "",
                transaction_coins: [{}],
                transaction_coin: {},
                order_amount: "",
                order_price: "",
                order_total: "",
                trans_type: "",
                trans_errors: {},
                wallet_balance: 0,
                coin_available: "",
                orderSuccess: false,
                price_negative: true,
                additionalInfoIndexesToShow: []
            };
        },
        watch: {
            transaction_coins: function () {
                this.transaction_coin = this.setDefaultCoin();
                this.Content();
            },

            order_amount: function () {
                if (
                    this.order_amount != "" &&
                    this.order_price != "" &&
                    this.order_amount > 0 &&
                    this.order_price > 0
                ) {
                    this.price_negative = false;
                }

                if (this.order_total <= this.wallet_balance) {
                    this.trans_errors.order_total = "";
                }

                if (this.order_amount <= 0 && this.order_amount != "") {
                    this.trans_errors.order_amount =
                        "Please enter a value greater than Zero";
                    this.price_negative = true;
                    return false;
                }

                this.calculateOrder();

                if (
                    this.trans_errors.order_amount != "" ||
                    this.trans_errors.order_amount == ""
                ) {
                    this.trans_errors.order_amount = "";
                }
            },

            order_price: function () {
                if (
                    this.order_amount != "" &&
                    this.order_price != "" &&
                    this.order_amount > 0 &&
                    this.order_price > 0
                ) {
                    this.price_negative = false;
                }

                this.calculateOrder();

                if (this.order_total <= this.wallet_balance) {
                    this.trans_errors.order_total = "";
                }

                if (this.order_price <= 0 && this.order_price != "") {
                    this.trans_errors.order_price =
                        "Please enter a value greater than Zero";
                    this.price_negative = true;
                    return false;
                }

                if (
                    this.trans_errors.order_price != "" ||
                    this.trans_errors.order_price == ""
                ) {
                    this.trans_errors.order_price = "";
                }
            }
        },
        mounted() {
            this.listenCoins();
            this.getTransactionCoins();
        },
        methods: {
            showAdditionalInfo(index) {
                let additionalInfoIndexesToShow = this.additionalInfoIndexesToShow.slice();
                if (additionalInfoIndexesToShow.indexOf(index) > -1) {
                    additionalInfoIndexesToShow.splice(
                        additionalInfoIndexesToShow.indexOf(index),
                        1
                    );
                } else {
                    additionalInfoIndexesToShow.push(index);
                }
                this.additionalInfoIndexesToShow = additionalInfoIndexesToShow.slice();
            },
            listenCoins: function () {
                let self = this;

                Echo.channel("market-view-coin").listen(".coinchanges", data => {
                    Vue.set(self.content, data.coin_id, data);
                });

                Echo.channel("market-view-update").listen(
                    ".marketupdated",
                    market_data => {
                        this.Content();
                    }
                );
            },

            Content() {
                $("#spinner").addClass("spinner-bg");
                $("#loader-div").addClass("loading");

                let data = {
                    coin_coin: this.transaction_coin.coin_coin
                };

                $.ajax({
                    type: "GET",
                    url: "/getCoindetails",
                    data: data,
                    cache: false,
                    success: function(response, status, error) {
                        this.content = response.data;

                        $("#loader-div").removeClass("loading");
                        $("#spinner").removeClass("spinner-bg");
                    }.bind(this),
                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            Edit(id) {
                this.coin.id = id;
                $("#update-Modal").appendTo('body').modal("show");
                var id = id;
                var url = "getCoindata/" + id;
                this.$http.get(url).then(response => {
                    this.coin.title = response.body.data.coin_title;
                    this.coin.symbol = response.body.data.symbol;
                    this.coin.description = response.body.data.coin_description;
                    this.coin.market = response.body.data.coin_market;
                    this.coin.id = response.body.data.coin_id;
                });

                this.Content();
            },

            Delete(id) {
                var id = id;
                this.$swal({
                    title: "Are you sure?",
                    text: "You want to delete this coin",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, keep it"
                }).then(result => {
                    if (result.hasOwnProperty("dismiss")) return false;

                    let url = "getCoindelete/" + id;
                    this.$http.get(url).then(response => {
                        this.Content();
                    });
                });
            },

            Coinupdate() {
                var id = this.coin.id;
                var url = "/updateCoindetails/" + id;
                this.$http.post(url, this.coin).then(response => {
                    this.errors = [];
                    if (response.body.success == 0) {
                        this.formErrors = response.body.data;
                    } else {
                        this.message = "Coin has been Updated.";
                        this.success = true;
                        $("#update-Modal").modal("hide");
                    }
                    this.data = {
                        title: "",
                        description: "",
                        mrket: "",
                        id: ""
                    };
                });
            },

            onFileAdded() {
                this.showRemoveAllButton = true;
            },

            onFileRemoved() {
                this.showRemoveAllButton = false;
            },

            onSuccess(file) {
                this.data = JSON.parse(file.xhr.response);
                if (this.data.status == 0) {
                    this.fileerror = this.data.data.file;
                    this.errorflag = true;
                    this.removeAll();
                } else {
                    this.data = JSON.parse(file.xhr.response);
                    this.filename = this.data.data;
                    this.message = "logo upload Successfully.";
                    this.fileerror = {};
                    this.errorflag = true;
                    this.success = true;
                }
            },

            removeAll() {
                this.$refs.droplyOne.removeAllFiles();
            },

            buysell_model: function (coin, type) {
                let title = "Invalid Operation";
                let msg =
                    "Select another coin. You can't buy or sell " +
                    coin.symbol +
                    " with " +
                    coin.symbol +
                    " itself!";
                let msg_type = "info";
                let cancel_txt = "Ok Got It !";
                let btn_txt = "";

                if (this.transaction_coin.coin_coin === coin.symbol) {
                    this.showAlert(title, msg, msg_type, btn_txt, cancel_txt, "", "");
                    return;
                }

                this.wallet_balance = 0;
                this.order_amount = "";
                this.order_price = "";
                this.order_total = "";
                this.trans_errors = {};
                this.trans_type = type;

                title = "NO EXISTING ORDERS";
                msg =
                    "You have ZERO order for " +
                    coin.symbol +
                    ". Buy " +
                    coin.symbol +
                    "!!!";
                msg_type = "warning";
                cancel_txt = "Ok Got It !";
                btn_txt = "Buy Now";
                let url = "/vue-dashboard/fetch-orders-executed";

                this.coin_available = coin.symbol;

                let post_data = {
                    coin_id: coin.coin_id
                };

                if (type == "BUY") {
                    post_data = {
                        coin_id: this.transaction_coin.coin_id
                    };
                    title = "ZERO BALANCE";
                    msg =
                        "Your " +
                        this.transaction_coin.coin_coin +
                        " balance is ZERO. Please load your wallet.";
                    btn_txt = "Go to Wallet Page";
                    url = "/vue-dashboard/fetch-wallet";

                    this.coin_available = this.transaction_coin.coin_coin;
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: post_data,
                    cache: false,
                    success: function(response, status, error) {
                        if (response == 0) {
                            this.showAlert(
                                title,
                                msg,
                                msg_type,
                                btn_txt,
                                cancel_txt,
                                coin,
                                type
                            );
                            return;
                        }

                        let amount = response.amount == null ? 0 : response.amount;
                        let inorder = response.inorder == null ? 0 : response.inorder;

                        this.wallet_balance = numeral(amount)
                            .subtract(inorder)
                            .value();
                        this.selected_coin = coin;

                        this.order_total = null;
                        $("#buy_sell_model").appendTo('body').modal("show");
                    }.bind(this),

                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            showAlert: function (title, msg, msg_type, btn_txt, cancel_txt, coin, type) {
                var self = this;

                this.$swal({
                    title: title,
                    text: msg,
                    type: msg_type,
                    confirmButtonText: btn_txt,
                    cancelButtonText: cancel_txt,
                    showCancelButton: true,
                    showConfirmButton: btn_txt == "" ? false : true
                })
                    .then(result => {
                        if (result.hasOwnProperty("dismiss")) return false;

                        if (type == "BUY") {
                            location.href = "#/wallet";
                        } else {
                            self.buysell_model(coin, "BUY");
                        }
                    })
                    .catch(this.$swal.noop);
            },

            /** want to make common **/
            getTransactionCoins: function () {
                $.ajax({
                    type: "GET",
                    url: "/vue-dashboard/coins",
                    cache: false,
                    success: function(response, status, error) {
                        this.transaction_coins = response;
                    }.bind(this),

                    error: function (data, status, error) {
                        console.log(error);
                    }
                });
            },

            set_transaction_coin: function (coin) {
                this.transaction_coin = coin;

                this.Content();
            },

            setDefaultCoin: function () {
                return _.find(this.transaction_coins, {coin_coin: "BTC"});
            },

            calculateOrder: function () {
                let total = numeral(this.order_amount)
                    .multiply(this.order_price)
                    .value();
                let formated = math.format(total, {
                    exponential: {lower: 0, upper: Infinity}
                });
                this.order_total = formated;

                if (this.order_total > this.wallet_balance && this.trans_type == "BUY") {
                    this.trans_errors.order_total =
                        "Total amount exceeds the wallet balance";
                }

                if (
                    this.order_amount > this.wallet_balance &&
                    this.trans_type == "SELL"
                ) {
                    this.trans_errors.order_amount =
                        "Total quantity exceeds the wallet balance";
                }
            },

            save_order: function () {
                let order = {
                    order_market: this.selected_coin.symbol,
                    order_amount: this.order_amount,
                    order_price: this.order_price,
                    order_total: this.order_total,
                    order_maincoin: this.transaction_coin.coin_coin,
                    order_coin_id: this.selected_coin.coin_id,
                    wallet_balance: this.wallet_balance,
                    order_type: this.trans_type === "BUY" ? 1 : 2,
                    order_maincoin_id: this.transaction_coin.coin_id
                };

                $.ajax({
                    type: "POST",
                    url: "/saveorders",
                    data: order,
                    cache: false,
                    success: function(response, status, error) {
                        if (response.errors) {
                            this.trans_errors = this.formatValidatorErrors(response.errors);
                        } else {
                            this.wallet_balance = numeral(this.wallet_balance)
                                .subtract(response.order_total)
                                .value();

                            this.orderSuccess = true;
                            let self = this;

                            setTimeout(function () {
                                self.orderSuccess = false;
                                self.order_amount = "";
                                self.order_price = "";
                                self.order_total = "";

                                $("#buy_sell_model").appendTo('body').modal("toggle");
                            }, 5000);
                        }
                    }.bind(this),
                    error: function (data, status, error) {
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
        }
    };
</script>

<style type="text/scss" lang="scss">
    #buy_sell_model.fade {
        opacity: 1;
    }

    #buy_sell_model .modal-dialog {
        top: 20%;
    }

    .order-action-button {
        width: 115px;
        height: 41px;
        box-shadow: 0 15px 30px rgba(0, 198, 255, 0.3);
        border-radius: 7px;
        background-color: #00c6ff;
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.28px;
        line-height: 41px;
        position: relative;

        &:disabled {
            background-color: #e1e9ee;
            color: #22272f;
            box-shadow: 0 15px 30px #e1e9ee;

        }
    }

    .order-action-button.dark {
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        background-color: #252525;

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
            height: 41px;

            &:hover {
                background-color: #fff !important;
            }
        }

        input:focus {

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
                & ~ .input-group-btn > .btn {
                    border-color: #003366;
                }
            }
        }
    }
</style>
