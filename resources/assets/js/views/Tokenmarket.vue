<template>
    <div class="content-page__right">
        <div class="list-exchange1">
            <div class="list-exchange1__item" v-for="token in tokens">
                <div class="list-exchange1__over">
                    <div class="list-exchange1__logo">
                        <img v-bind:src="'/img/coin/64/' + token.coin_coin + '.png'"
                             onerror="this.src='/img/coin/64/NOIMAGE.png'" class="img-responsive mx-auto d-block"
                             width="64px" height="64px">
                    </div>
                    <div class="list-exchange1__text">
                        <div class="list-exchange1__title">{{token.coin_title}}</div>
                        <p>{{token.coin_coin}}</p>
						<p class="card-text" v-bind:id="token.coin_address"></p>
					</div>
                    <router-link :to="'/tokenexchange/' + token.coin_coin" class="exchange1">Exchange</router-link>
                </div>
			</div>
		</div>
	</div>
</template>

<script>

    export default {
        name: 'tokenmarket',
        data() {
            return {
                tokens: []
            }
        },
        methods: {

            getTokens() {

                $('#spinner').addClass('spinner-bg');
                $('#loader-div').addClass('loading');

                $.ajax({
                    type: "GET",
                    url: '/token/list',
                    cache: false,
                    success: (response, status, error) => {

                        this.tokens = response.data;

                        $('#loader-div').removeClass('loading');
                        $('#spinner').removeClass('spinner-bg');

                        console.log(response.data);
                        var exchangers = [];
                        for(var i = 0 ; i < this.tokens.length ; i ++) {
                            exchangers.push({
                                exchangerAddress: this.tokens[i].coin_exchanger,
                                tokenAddress: this.tokens[i].coin_address,
                                network: this.tokens[i].coin_network
                            });
                        }

                        TokenOrderApp.boot(exchangers , '1' , (error, counts) => {
                            console.log('VUE RESULT', counts);

                            for (var i = 0; i < counts.length; i++) {
                                console.log(counts[i].makeCount);
                                $('#' + counts[i].tokenAddress).html('Open Orders : ' + counts[i].makeCount);
                            }
                        } , (error , count) => {
                            $('#' + count.tokenAddress).html('Open Orders : ' + count.makeCount);
                        });

                        $(document).trigger('exchangerReady');

                    },
                    error: function(data,status,error){
                        console.log(error);
                    }
                });

            }
        },
        beforeMount(){
            this.getTokens()
        }
    }
</script>

<style type="text/css">
    .exchange1:hover {
        text-decoration: none;
        color: white;
    }
</style>
