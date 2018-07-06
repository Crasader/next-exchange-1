<template>
    <section id="transaction" class="ptb40 bg--secondary">
        <div class="container">
            <h1>Transactions</h1>
            <table class="table next-table">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Date</td>
                    <td>TxID</td>
                    <td>Type</td>
                    <td>Buy/Sell</td>
                    <td>Amount</td>
                    <td>Confirmed</td>
                </tr>
                </thead>
                <tbody>

                <tr v-for="listdata in list">
                    <td>{{listdata.transaction_id}}</td>
                    <td>{{listdata.transaction_date}}</td>
                <td>
                    <span v-if="listdata.main_coin == 'BTC'"><a
                            v-bind:href="'https://blockchain.info/tx/' + listdata.rxid" target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'ETH'"><a
                            v-bind:href="'https://etherscan.io/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'XRP'"><a
                            v-bind:href="'https://bithomp.com/explorer/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'DASH'"><a
                            v-bind:href="'https://explorer.dash.org/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'LTC'"><a
                            v-bind:href="'https://bchain.info/LTC/tx/' + listdata.rxid" target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'XMR'"><a
                            v-bind:href="'https://moneroexplorer.com/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'ADA'"><a
                            v-bind:href="'https://cardanoexplorer.com/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'XEM'"><a
                            v-bind:href="'http://chain.nem.ninja/#/transfer/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'NEXT'"><a
                            v-bind:href="'https://etherscan.io/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'ETN'"><a
                            v-bind:href="'https://blockexplorer.electroneum.com/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'XVG'"><a
                            v-bind:href="'https://verge-blockchain.info/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'SKY'"><a
                            v-bind:href="'https://explorer.skycoin.net/app/transaction/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'ECA'"><a
                            v-bind:href="'https://www.electraexplorer.com/tx/' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else-if="listdata.main_coin == 'LYL'"><a
                            v-bind:href="'http://explorer.nemchina.com/#/s_tx?hash=' + listdata.rxid"
                            target="_blank">{{ listdata.rxid | truncate(32) }}</a></span>
                    <span v-else>{{ listdata.rxid | truncate(32) }}</span>
               </td>
                 <td>
                   <span v-if="listdata.transaction_type == 1">Deposit</span>
                   <span v-if="listdata.transaction_type == 2">Withdraw</span>
                   <span v-if="listdata.transaction_type == 3">Order Execution</span>
               </td>
               <td>
                   <span v-if="listdata.transaction_buysell == 1">Buy</span>
                   <span v-if="listdata.transaction_buysell == 2">Sell</span>
               </td>
                    <td>{{ listdata.amount }} <img v-bind:src="'/img/coin/16/'+listdata.main_coin+'.png'"
                                                   onerror="this.src='/img/coin/16/noimage.png'"
                                                   v-bind:title="listdata.main_coin"></td>
                    <td>
                   <i class="fa fa-check-circle text-success" v-if="listdata.status == 1" title="Confirmed"></i>
                   <i class="fa fa-exclamation-circle text-warning" v-if="listdata.status == 0" title="Unconfirmed"></i>
               </td>
             </tr>

             </tbody>
           </table>


            <!-- Pagination -->

            <ul class="pagination" role="navigation">
	            <li v-if="pagination.current_page > 1">
	                <a href="#" aria-label="Previous"
	                   @click.prevent="changePage(pagination.current_page - 1)">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
	                </a>
	            </li>
                <li class="page-item" v-for="page in pagesNumber"
                    v-bind:class="[ page == isActived ? 'active' : '']">
                    <a class="page-link" href="#"
                       @click.prevent="changePage(page)">{{ page }}</a>
	            </li>
	            <li v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#" aria-label="Next"
                       @click.prevent="changePage(pagination.current_page + 1)">
                        <span aria-hidden="true">&rsaquo;</span>
	                </a>
	            </li>
	        </ul>

        </div>
    </section>
</template>

<script>

    export default {
        name: 'transaction',

        data()
        {
			return {
				list:'',
				pagination: {
					total: 0, 
					per_page: 2,
					from: 1, 
					to: 0,
					current_page: 1
				},
			offset: 4,
			}
        },
        computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
        mounted()
        {
			this.fetchtransactions(this.pagination.current_page);
			   this.interval = setInterval(function () {
				this.fetchtransactions(this.pagination.current_page);
				
		  }.bind(this), 30000);
			
			
        },
        methods:
        {
			fetchtransactions(page)
			{
		
			 axios.get('api/transactions?page='+page).then((res) => {
			    this.list={};
				        
                this.list = res.data.data;

                //this.$set('pagination', res.data.pagination);
                
                this.pagination=res.data.pagination
            });
         
		},
			getRemaining() {
                axios.get(`api/transactions?page=${this.list.current_page}`)
                .then((response) => {
					this.list={};
                    this.list = res.data.data;
                })
                .catch(() => {
                    console.log('handle server error from here');
                });
      
        },
        changePage: function (page) {
          this.pagination.current_page = page;
          this.fetchtransactions(page);
      }
        }

    }
</script>

