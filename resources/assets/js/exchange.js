const numeral   = require('numeral');
export default {
    init_exchange ( exchange_vue ) {
        let rts = {};

        let amount = $('.input-amount');
        let badge = $('.exchange-rate-badge');
        //mock of exchange rates
        let exchangeRates ='';

        $('.currency-exchange-wallet-sel').select2({
            templateResult: format_wallet,
            templateSelection: format_wallet,
            selectOnClose: true
        });

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

        $('#currency-amount').select2({
            data: currencies,
            minimumResultsForSearch: -1
        });

        function format_wallet ( wallet ) {

            if (!wallet.id) {
                var $currencyH = $('<div class="currency-header flex"><span class="currency-name">'+ wallet.text +'</span><span class="balance-label">Available Balance</span>');
                return $currencyH;
            }

            if(wallet.element.attributes['data-balance']  === undefined ) {

                var $wallet = $(
                    '<div class="wallet-item flex"> <span class="wallet-name flex"><i class="material-icons md-18">account_balance_wallet</i>' + wallet.text + '</span><span class="balance"></span></div>'
                );
            } else {

                let balance = wallet.element.attributes['data-balance'].textContent;

                var $wallet = $(
                    '<div class="wallet-item flex"> <span class="wallet-name flex"><i class="material-icons md-18">account_balance_wallet</i>' + wallet.text + '</span><span class="balance">' + balance + '</span></div>'
                );
            }

            return $wallet;
        }

        //Utility methods
        function getAllCurrencies() {

            let allCurrencies = [];
            $("#from option").each(function () {
                allCurrencies.push({ value: this.value, currency: this.getAttribute('data-currency') });
            });
            return allCurrencies
        }

        function getSymbolFromCurrency(currency) {

            var symbol;
            switch (currency) {
                case 'EUR':
                    symbol = "€";
                    break;
                case 'GBP':
                    symbol = "£";
                    break;
                case 'USD':
                    symbol = "$";
                    break;
                default:
                    symbol = ""
            }
            return symbol
        }


        // wallet with same currency selection logic
        var fromV, toV;

        function getValueFirstDifferentCurrency(currency) {
            let c = getAllCurrencies();
            return c.find(function (element) {
                return element.currency != currency
            }).value
        }

        //from select

        // store current value
        $('#from').on("select2:selecting", function (e) {
            fromV = $("#from :selected").attr("value");
            toV = $("#to :selected").attr("value");
        });

        $('#from').on("select2:select", function (e) {

            if($('#select-error').hasClass('d-block')) {

                $('#select-error').removeClass('d-block');
                $('#select-error').addClass('d-none');
            }

            var data = e.params.data;

            let fromC = data.element.attributes['data-currency'].value;
            let toC = $("#to :selected").attr("data-currency");

            if(fromC === '-- Select a Coin --' || toC === '-- Select a Coin --')
            {
                Vue.set(exchange_vue, 'exchange_order', {});

                currencies[0].text = '-';
                currencies[1].text = '-';

                currencies[0].selected = true;
                currencies[1].selected = false;

                // let's reset the currency elector with the new 2 currencies
                $('#currency-amount').select2('destroy').empty().select2({ data: currencies, minimumResultsForSearch: -1 }).trigger("change");
            } else {

                if(fromC == toC) {

                    $('#select-error').removeClass('d-none');
                    $('#select-error').addClass('d-block');

                    $('.summary').hide();
                    badge.hide();
                } else {

                    if($('#save-error').hasClass('d-block')) {

                        $('#save-error').removeClass('d-block');
                        $('#save-error').addClass('d-none');
                    }

                    exchangeCurrencyrates('CTR',fromC,toC,function(callback){

                        exchangeRates   = JSON.parse(callback);
                    });

                    getCurrencySelected();
                    updateDeltas();
                }
            }
        });

        //store currenct value
        $('#to').on("select2:selecting", function (e) {
            fromV = $("#from :selected").attr("value");
            toV = $("#to :selected").attr("value");
        });

        $('#to').on("select2:select", function (e) {

            if($('#select-error').hasClass('d-block')) {

                $('#select-error').removeClass('d-block');
                $('#select-error').addClass('d-none');

                $('.summary').hide();
                badge.hide();
            }

            var data    = e.params.data;
            let fromC   = $("#from :selected").attr("data-currency");
            let toC     = data.element.attributes['data-currency'].value;

            if(fromC === '-- Select a Coin --' || toC === '-- Select a Coin --')
            {

                Vue.set(exchange_vue, 'exchange_order', {});

                currencies[0].text = '-';
                currencies[1].text = '-';

                currencies[0].selected = true;
                currencies[1].selected = false;

                // let's reset the currency elector with the new 2 currencies
                $('#currency-amount').select2('destroy').empty().select2({ data: currencies, minimumResultsForSearch: -1 }).trigger("change")

            } else {

                if(fromC == toC) {

                    $('#select-error').removeClass('d-none');
                    $('#select-error').addClass('d-block');
                } else {

                    if($('#save-error').hasClass('d-block')) {

                        $('#save-error').removeClass('d-block');
                        $('#save-error').addClass('d-none');
                    }

                    let val = getValueFirstDifferentCurrency(toC);

                    exchangeCurrencyrates('CTR',fromC,toC, function(callback){

                        exchangeRates     = JSON.parse(callback);
                    });

                    getCurrencySelected();
                    updateDeltas();
                }
            }


        });

        function getCurrencySelected() {

            let fromC = $("#from :selected").attr("data-currency");
            let toC = $("#to :selected").attr("data-currency");


            let sF = getSymbolFromCurrency(fromC);
            //alert('fromC --- '+ fromC +' `toC --- '+ toC +' sF --- ' + sF + ' sT --- '+ sT);
            let selC = $("#currency-amount :selected")[0].value;
            //alert('selC --- '+selC);
            currencies[0].text = fromC + (sF !== "" ? ' (' + sF + ')' : '');
            currencies[1].text = toC;

            // in here we check if the user selected the first of the second currency for the amount. and we remember it
            if (selC == 0) {
                currencies[0].selected = true;
                currencies[1].selected = false
            } else {
                currencies[0].selected = false;
                currencies[1].selected = true
            }

            // let's reset the currency elector with the new 2 currencies
            $('#currency-amount').select2('destroy').empty().select2({ data: currencies, minimumResultsForSearch: -1 }).trigger("change")

        }


        $('#currency-amount').on('change', function (e) {

            let selC = $("#currency-amount :selected").text();

            if(selC !== '-') {

                $('.input-currency').text(getSymbolFromCurrency(selC.toString()));
                updateDeltas();
            }
        });

        // this method updates the dynamic text around the interface
        function updateDeltas() {

            let balanceFrom;
            let balanceTo;
            let differenceFrom;
            let differenceTo;

            let fromW   = $('#from :selected').text();
            let toW     = $('#to :selected').text();

            let firstC  = currencies[0].text;
            let secC    = currencies[1].text;
            let amaV    = getMoneyAmount(amount);

            let selC = $("#currency-amount :selected")[0].value;
            let deltaFrom;
            let deltaTo;

            $('.delta-from').show();
            $('.delta-to').show();

            if (selC == 0) {

                //if we are converting using the 'from wallet' currency we keep the same amount of the input as a negative variation under the from wallet selector.
                // And on the 'To wallet' we convert the amount in the destination exchange rate as a positive variation under the to wallet selector.
                //  we use utility methods to retrieve the correct symbol and format the number correctly

                deltaFrom = getSymbolFromCurrency($.trim(firstC.substring(0, firstC.length - 4))) + formatMoney(amaV);

                deltaTo = formatMoney(getValueFromCurrencyToCurrency(selC, amaV, firstC, secC));

                $('.delta-from').text('-' + deltaFrom);
                $('.delta-to').text('+' + deltaTo);

                badge.show();
                badge.text('1 '+secC+' = '+ firstC+ ' ' + getRateFromCurrencyToCurrency(selC,firstC,secC));
            }
            else if (selC == 1) {

                //if we are converting using the 'to wallet' we convert the input amount in the starting exchange rate as a negative variation under the from wallet selector.
                // in the ' To wallet' we keep the same amount as a positive variation under the to wallet selector.
                // we use utility methods to retrieve the correct symbol and format the number correctly

                deltaFrom = getSymbolFromCurrency($.trim(firstC.substring(0, firstC.length - 4))) + formatMoney(getValueFromCurrencyToCurrency(selC,amaV, secC, firstC));
                deltaTo = getSymbolFromCurrency(secC.toString()) + formatMoney(amaV);

                $('.delta-from').text('-' + deltaFrom);
                $('.delta-to').text('+' + deltaTo);

                badge.text('1' + secC + '  = '+ firstC + ' ' + getRateFromCurrencyToCurrency(selC, secC, firstC));
            }

            //do the math
            balanceFrom = numeral($('#from :selected')[0].attributes['data-balance'].textContent);

            if($('#to :selected')[0].attributes['data-balance'].hasOwnProperty('textContent')) {

                balanceTo = numeral($('#to :selected')[0].attributes['data-balance'].textContent);
            } else {

                balanceTo   = {_value: 0};
            }


            if(selC === 0)
            {

                differenceFrom = balanceFrom._value - numeral(deltaFrom.toString())._value;
                differenceTo = balanceTo._value + ( numeral(deltaTo.toString())._value);
            }
            else{

                differenceFrom = balanceFrom._value - numeral(deltaFrom.toString())._value;
                differenceTo =  ( numeral(deltaTo.toString())._value) + balanceTo._value;
            }

            let order_price = numeral(numeral(deltaFrom).value()).divide(numeral(deltaTo).value()).value();
            exchange_vue.exchange_order = {

                order_maincoin_id: $('#from :selected').val(),
                order_market: toW,
                order_price: order_price,
                order_total: numeral(deltaFrom).value(),
                order_type: 1,
                order_maincoin: fromW,
                order_coin_id: $('#to :selected').val(),
                order_amount: numeral(deltaTo).value(),
                order_exchange: 1,
                wallet_balance: balanceFrom._value
            };

            //at last update the summary text
            var summaryText = 'The new balance of '+ fromW +' will be '+  getSymbolFromCurrency($.trim(firstC.substring(0, firstC.length - 4))) + formatMoney(differenceFrom)
                +'  and the new balance of '+ toW +' will be '+ formatMoney(differenceTo);

            if (formatMoney(differenceFrom) <= 0) {

                $('#low-balance').removeClass('d-none');
                $('#low-balance').addClass('d-block');
                $('#btn-exchange').attr('disabled', 'disabled');
            } else {

                $('#low-balance').removeClass('d-block');
                $('#low-balance').addClass('d-none');
                $('#btn-exchange').removeAttr('disabled');
            }

            $('.summary').show();
            $('.summary').text(summaryText);
            var order_amt=formatMoney(differenceFrom);

            $('#total_amt').val(order_amt);
        }

        function getValueFromCurrencyToCurrency(selC, amount, a, b) {

            let convertedAmt;

            if(selC==0)
            {

                var string2     = b ==='NEM' ? 'XEM' : b;

                var string_trimmed      = $.trim(a.substring(0, a.length - 4));
                var string1             = string_trimmed.length > 0 ? string_trimmed : a;
                var rates       = exchangeRates[string1].quotes[string2];
                convertedAmt    = amount / rates;
            }
            else{

                var string2         = a ==='NEM' ? 'XEM' : a;

                var string_trimmed  = $.trim(b.substring(0, b.length - 4));

                var string1         = string_trimmed.length > 0 ? string_trimmed : b;

                var rates       = exchangeRates[$.trim(string1)].quotes[string2];
                convertedAmt    = amount * rates;
            }

            return convertedAmt;
        }

        function getRateFromCurrencyToCurrency(selC,a, b) {

            if(selC==0)
            {
                var string2     = b ==='NEM' ? 'XEM' : b;
                var string_trimmed      = $.trim(a.substring(0, a.length - 4));
                var string1             = string_trimmed.length > 0 ? string_trimmed : a;

                var rates   = exchangeRates[string1].quotes[string2];
            }
            else{

                var string2     = a ==='NEM' ? 'XEM' : a;
                var string_trimmed      = $.trim(b.substring(0, b.length - 4));
                var string1             = string_trimmed.length > 0 ? string_trimmed : b;

                var rates   = exchangeRates[string1].quotes[string2];
            }
            rts = parseFloat(rates).toFixed(9);
            return rts;
        }

        function getMoneyAmount(amount) {
            return numeral(amount.val()).value();
        }

        function formatMoney(amount) {
            return amount > 0 ? numeral(amount).format('0,0.000000000') : '0.00'
        }

        //update the values while typing, should be on change if we want to simulate the current BC behaviour
        // amount.keydown(function () {
        //   updateDeltas();
        // })
        amount
        // event handler
            .keyup(resizeInput)
            .keyup(updateDeltas)
            // resize on page load
            .each(resizeInput);

        amount.on('change', function (e) {
            let selC = $("#currency-amount :selected").text();

            if(selC !== '-') {
                updateDeltas();
            }
        });

        function resizeInput() {

            if($(this).val() > 0) {

                if($('#save-error').hasClass('d-block')) {

                    $('#save-error').removeClass('d-block');
                    $('#save-error').addClass('d-none');
                }
            }
            $(this).attr('size', $(this).val().length);
        }

        function exchangeCurrencyrates(conversion_type, a,b,callback)
        {
            var data    = {'to':b,'from':a,'conversion_type':conversion_type};
            var currentRequest = null;
            if (currentRequest) {
                currentRequest.abort();
            }
            currentRequest  = $.ajax({
                "type":"POST",
                "url":'/getRates',
                beforeSend : function()    {
                    if(currentRequest != null) {

                        currentRequest.abort();
                    }
                },
                'data':data,
                "dataType":"json",
                "async":false,
                "success": callback
            });
        }
    }
}