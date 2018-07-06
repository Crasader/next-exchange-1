<template>

  <div class="animated fadeIn">
    <div class="spinner-wrapper">
    <div class="container">
      <form id="exchange-form">
        <div class="form-group">
          <div class="row wallets">
            <div class="col">
              <label for="from">From wallet </label>
              <select id="from" class="currency-exchange-wallet-sel" name="wallet">
               <optgroup v-for="(currency, index) in fiat_currencies" :label="currency.coin_coin">
                   <option :value="currency.coin_id" :data-currency="currency.coin_coin" v-if="index===0" >{{ currency.coin_coin }}</option>
                   <option :value="currency.coin_id" :data-currency="currency.coin_coin" :data-balance="get_currency_symbol(currency.coin_coin) + currency.current_balance" v-else >{{ currency.coin_coin }}</option>
                </optgroup>
              </select>
              <pre class="delta-from"></pre>
            </div>
            <div class="col">
              <label for="to">To crypto</label>
              <select id="to" class="currency-exchange-wallet-sel" name="wallet">
                <optgroup v-for="(digital_currency, index) in digital_currencies" :label="digital_currency.coin_coin">
                  <option :value="digital_currency.coin_id" :data-currency="digital_currency.coin_coin"  v-if="index===0"  >{{digital_currency.coin_coin}}</option>
                    <option :value="digital_currency.coin_id" :data-currency="digital_currency.coin_coin" :data-balance="digital_currency.current_balance" v-else >{{digital_currency.coin_coin}}</option>
                </optgroup>
              </select>
              <pre class="delta-to"></pre>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row amount">
            <div class="col">
              <label for="input-amount" class="text-center w-100">Insert amount</label>
              <div class="smart-input form-control flex">
                <select id="currency-amount" name="currency-amount" style="width:94px" >
                </select>
                <div class="input-group input-group-lg input-group-amount">
                    <cleave id="input-amount" :options="{numeral: true, numeralDecimalMark: '.', delimiter: ','}"/>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="row">
          <div class="col">
          <pre>
              <div class="exchange-rate-badge"> </div>
            <div class="summary text-center"></div>
<transition name="fade"><div class="alert alert-success text-center" style="font-weight: bold" v-if="exchange_success"> Your order for exchanging {{ exchange_order.order_total|format_decimals }}-{{ exchange_order.order_maincoin }} to {{ exchange_order.order_amount|format_decimals }}-{{  exchange_order.order_market }} is placed.</div></transition>
            <div class="text-danger text-center d-none" id="low-balance">You don't have enough balance in your wallet, To load your wallet <a href="/wallet">CLICK HERE</a> </div>
             <div class="text-danger text-center d-none" id="save-error">Please fill all fields (*All fields are required) </div>
             <div class="text-danger text-center d-none" id="select-error">You can't exchange same currencies together, Select different currencies</div>
            </pre>
          </div>
        </div>
	<input type="hidden" id="total_amt">
        <div class="row">
          <div class="col text-center"><button id="btn-exchange" class="btn btn-primary" type="button" v-on:click='exchange'>EXCHANGE CURRENCY</button></div>
        </div>
    </form>
  </div>
      <div id="loader-div" class=""></div>
      <div id="spinner" class=""></div>
    </div>
  </div>
</template>

<script>

    import Exchange from '../exchange';
    import Transaction from "./Blacklist";
    import Cleave from 'vue-cleave';

    let numeral     = require('numeral');

    export default {
        components: {
            Cleave,
            Transaction
        },
        name: 'exchange',
        data()
        {
			return {

				fiat_currencies: [],
                digital_currencies: [],

				exchange_order: {},
                exchange_success: false,

				currency_symbols: {
                    'USD': '$',
                    'EUR': '€',
                    'GBP': '£'
                }
			}
        },
        mounted()
        {
            $('#spinner').addClass('spinner-bg');
            $('#loader-div').addClass('loading');

			this.fetch();

        },
        filters: {

            format_decimals: function ( value ) {

                return numeral(value).format('0.00');
            }
        },
        methods:
        {
			fetch: function() {

			    let self    = this;

			    $.getJSON('/exchangewalletdetails', function ( response ) {

			        Vue.set( self, 'fiat_currencies', response.currencies);
			        Vue.set( self, 'digital_currencies', response.digital_currencies);

                    self.$nextTick(function () {
                        $("#from").val($("#from option:eq(0)").val()).trigger('change');
                        $("#to").val($("#to option:eq(0)").val()).trigger('change');
                    });

			        self.fiat_currencies.unshift({
                        'coin_id': '0',
                        'coin_coin': '-- Select a Coin --',
                        'current_balance': '0'
                    });

                    self.digital_currencies.unshift({
                        'coin_id': '0',
                        'coin_coin': '-- Select a Coin --',
                        'current_balance': '0'
                    });

                    Exchange.init_exchange(self);

                    $('#spinner').removeClass('spinner-bg');
                    $('#loader-div').removeClass('loading');
                });
		    },

            get_currency_symbol: function ( coin ) {

                return this.currency_symbols.hasOwnProperty(coin) ? this.currency_symbols[coin] : '';
            },

            exchange: function() {

			    if( !Object.keys(this.exchange_order).length || this.exchange_order.order_total === 0 )  {

                    $('#save-error').removeClass('d-none');
                    $('#save-error').addClass('d-block');

			        return false;
                }

                $('#btn-exchange').attr('disabled', 'disabled');

			    this.exchange_success   = true;

			    var self    = this;
                axios.post('saveorders', this.exchange_order).then((res) => {

                    let index = self.fiat_currencies.findIndex(x => x.coin_coin == self.exchange_order.order_maincoin);
                    self.fiat_currencies[index].current_balance = numeral(self.fiat_currencies[index].current_balance).subtract(self.exchange_order.order_total).value();

                    index = self.digital_currencies.findIndex(x => x.coin_coin == self.exchange_order.order_maincoin);

                    if(index >= 0) {

                        self.digital_currencies[index].current_balance = numeral(self.digital_currencies[index].current_balance).subtract(self.exchange_order.order_total).value();
                    }


                    setTimeout(function () {

                        Vue.set(self, 'exchange_order', {});


                        $("#from").val($("#from option:eq(0)").val()).trigger('change');
                        $("#to").val($("#to option:eq(0)").val()).trigger('change');

                        let currencies = [
                            {
                                "id": 0,
                                "text": "-",
                                "selected": true
                            },
                            {
                                "id": 1,
                                "text": "-"
                            }
                        ];
                        $('#currency-amount').select2('destroy').empty().select2({ data: currencies, minimumResultsForSearch: -1 }).trigger("change");

                        $('#input-amount').val('0.00');

                        $('.exchange-rate-badge').hide();

                        $('.summary').hide();

                        $('.delta-from').hide();
                        $('.delta-to').hide();

                        self.exchange_success   = false;
                        $('#btn-exchange').removeAttr('disabled');
                    }, 5000);


                });
            },
    }
}

</script>

