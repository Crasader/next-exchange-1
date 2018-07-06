<template>
    <div class="content-page__right">
        <div class="block1" v-if="user_type == 1">
            <div class="f-group">
                <input type="text" placeholder="Search By IP..." class="search-top-field" name="search_ip"
                       v-model="searechByIp">
            </div>
            <div class="input-group">
                <input class="form-control search-top-field" placeholder="Add IP" type="text" v-model="ip_address">
                <span class="input-group-btn">
                    <button class="btn-submit" type="button" v-on:click="addNewIpAddress">Submit >></button>
                </span>
            </div>
            <table class="table2">
                <thead>
                <td>Id</td>
                <td>IP Address</td>
                <td>Action</td>
                </thead>
                <tbody>
                <tr v-for="(listdata, index) in  list.data">

                    <td> {{ index + 1}} </td>
                    <td> {{ listdata.ip_address}} </td>
                    <td>
                        <button type="button" v-on:click="whitelisted(listdata.ip_address,0)" class="btn-new">
                            Click To Whitelisted
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <ul class="pager">
                <li class="previous" v-show="list.previous">
                    <a class="page-scroll" v-on:click="getRemaining('previous')" href="#">Previous</a>
                </li>
                <li class="next" v-show="list.next">
                    <a class="page-scroll" v-on:click="getRemaining('next')" href="#">Next</a>
                </li>
            </ul>
            <!-- Pagination -->
            <nav class="pagination-nav">
                <ul class="pagination">
                    <li :class="pagination.current_page == 1 ? 'disabled' : '' ">
                        <a href="#" aria-label="Previous"
                           @click.prevent="changePage(pagination.current_page - 1)">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <li v-for="page in pagesNumber"
                        v-bind:class="[ page == isActived ? 'active' : '']">
                        <a href="#"
                           @click.prevent="changePage(page)">{{ page }}</a>
                    </li>
                    <li :class="pagination.current_page == pagination.last_page ? 'disabled' : ''">
                        <a href="#" aria-label="Next"
                           @click.prevent="changePage(pagination.current_page + 1)">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div v-if="user_type == 0">
            <div class="block1">
                <div class="soon-block1">
                    <div class="soon-block1__title1">403</div>
                    <h2 class="smooth">You are not authorized to access this page.</h2>
                    <div class="soon-block1__line"></div>
                    <p> Go to Dashboard.
                        <router-link to="/markets">Click Here.</router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .smooth {
        color: #7a86a8;
    }
</style>

<script>

    export default {
        name: 'transaction',

        data()
        {
            return {
                list:'',
                ipCheck: 0,
                user_type: 1,
                ip_address:"",
                searechByIp:"",
                list_count: 1,
                btcgenerated: false,
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
            this.fetchblacklist(this.pagination.current_page);
            this.interval = setInterval(function () {
                this.fetchblacklist(this.pagination.current_page);
            }.bind(this), 8000);
        },
        methods:
            {
                fetchblacklist(page)
                {
                    axios.get('api/black_list?page='+page+'&search='+this.searechByIp).then((res) => {
                        this.list={};
                        this.list = res.data.data;
                        this.list_count= 1;
                        this.pagination=res.data.pagination
                    });
                },
                getRemaining() {
                    axios.get('api/black_list?page=${this.list.current_page}&search='+this.searechByIp)
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
                    this.fetchblacklist(page);
                },

                whitelisted(id,type){
                    axios.get('api/whitelisted?ip='+id+'&type='+type)
                        .then((response) => {

                        })
                        .catch(() => {
                            console.log('handle server error from here');
                        });
                },

                searchByIp(){
                    axios.get('api/black_list?page=${this.list.current_page}&search='+this.searechByIp)
                        .then((response) => {
                            this.list={};
                            this.list = res.data.data;
                        })
                        .catch(() => {
                            console.log('handle server error from here');
                        });
                },
                addNewIpAddress(){

                    axios.get('api/blacklisted?ip='+String(this.ip_address))
                        .then((response) => {
                            this.ipCheck = 0;
                            this.ip_address = "";
                            this.searechByIp = "";

                        })
                        .catch(() => {
                            console.log('handle server error from here');
                        });

                },
                getUser: function() {
                    axios.get('api/currentUser').then((res) => {
                        this.user_type = res.data.user_type;
                    });
                },
            },
        beforeMount(){
            this.getUser();
        }

    }
</script>
