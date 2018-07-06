<template>
    <div class="content-page__right">
        <div class="block1">
            <div class="title-top1">
                <div class="title-top1__left">(PRE)ICO listing</div>
            </div>
            <div class="list-text1">
                <div class="list-text1__item mod1">
                    <div class="text1">
                        <div class="text1__title1">Be the first when new (pre)ICO are announced</div>
                        <p>
                            Only on NEXT, you will find the best ICO’s which will hit the market. And the best of all, after the ICO is finished, it’s directly tradeable on NEXT.</p>
                    </div>
                </div>
                <div class="list-text1__item">
                    <div class="text1">
                        <div class="text1__title2">MINE</div>
                        <a href="" class="operational1">Operational</a>
                        <p>
                            Invest in a large and professional mining farm for cryptocurrencies in a private datacenter.</p>
                    </div>
                </div>
                <div class="list-text1__item">
                    <table class="table4">
                        <tr>
                            <td>
                                <span>Location:</span>
                                <p>the Netherlands</p>
                            </td>
                            <td>
                                <span>Soft-Hard cap:</span>
                                <p>300-3000 ETH</p>
                            </td>
                            <td>
                                <span>ROI:</span>
                                <p>300%</p>
                            </td>
                            <td>
                                <span>ICO Dates:</span>
                                <p>March 15th - April 30th, 2018</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>ICO Bonus:</span>
                                <p>40% till March 31th</p>
                            </td>
                            <td>
                                <span>Price:</span>
                                <p>1 ETH = 4032 MINE (incl. bonus)</p>
                            </td>
                            <td>
                                <span>Send ETH to:</span>
                                <p>0x2Ef35963b7afaA07B3d5d0d36eA4dE0cC3Ae2405</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="list-text1__item">
                    <div class="text1__title2">Add Whitelist Address</div>
                    <form class="small-form-address1">
                        <input class="small-form-address1__input" id='eth' placeholder="Enter ETH address.." required
                               type="text" v-model="eth">
                        <button class="small-form-address1__submit" v-on:click="addaddress()" type="button">Submit
                        </button>
                        <span v-if="errorflag">Whitelist address is required</span>
                    </form>
                </div>
                <div id="loader-div" class=""></div>
                <div id="spinner" class=""></div>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
    form.small-form-address1 {
        border: 0;
        padding: 0 110px 0 0;
        margin: 0;
    }

    form.small-form-address1 > input {
        transition: all 9999s;
        margin-top: 3px;
        font-size: 12px;
        border: 0;
    }

    p {
        margin: 0;
    }

    .list-text1__item > .text1 > a {
        color: white;
        text-decoration: none;
    }
</style>

<script>

    export default {
        name: 'ico',

        data() {
            return {
                addrss :0,
                errorflag: false,
                eth: '',
                ico_id: 75,
            }
        },
        methods: {
            addaddress() {
                let self    = this;
                if(this.eth)
                {
                    $('#spinner').addClass('spinner-bg');
                    $('#loader-div').addClass('loading');
                    $('body').find('.btn btn-success').attr('disabled','disabled');

                    let data = {
                        'eth': this.eth,
                        'ico_id': this.ico_id
                    };

                    axios.post('/icowhitelist', data).then((res) => {
                        console.log(res);
                        if( res.data.success == 1 ) {
                            this.addrss = 0;
                            this.eth = '';
                            $('#spinner').removeClass('spinner-bg');
                            $('#loader-div').removeClass('loading');
                            self.$swal({
                                title: 'ICO Address',
                                text: "You have successfully whitelist your address",
                                type: 'success',
                                confirmButtonText: 'OK',
                                showConfirmButton: true

                            }).then((result)=>{
                                if(result.hasOwnProperty('dismiss')) return false;
                                if (result) {
                                }
                            }).catch(this.$swal.noop);

                        }
                        else {

                            this.errorflag  = true;
                            $('#spinner').removeClass('spinner-bg');
                            $('#loader-div').removeClass('loading');
                        }
                    });

                }else{
                    this.errorflag = true;
                }
            }
        }

    }
</script>
