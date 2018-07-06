<template>
    <div class="content-page__right">
        <div v-if="user_type == 1" class="block1">
            <div class="f-group">
                <input type="text" class="search-top-field form-control" placeholder="Search By Email..."
                       name="search_ip" v-model="searechByEmail">
            </div>
            <table class="table2">
                <thead>
                <td>Id</td>
                <td>Email</td>
                <td>IP</td>
                <td>Action</td>
                </thead>
                <tbody>
                <tr v-for="(listdata, indexs) in  list.data">
                    <td> {{ indexs + 1 }} </td>
                    <td> {{ listdata.email }} </td>
                    <td> {{ listdata.user_ips.ip}} </td>
                    <td v-if="listdata.is_block == 1">
                        <button type="button" v-on:click="whitelisted(listdata.user_ips.ip,0)" class="btn-new">
                            Click To Whitelisted
                        </button>
                    </td>
                    <td v-if="listdata.is_block == 0">
                        <button type="button" v-on:click="whitelisted(listdata.user_ips.ip,1)" class="btn-new">
                            Click To Blacklisted
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

<script>

    export default {
        name: 'user_list',

        data()
        {
            return {
                searechByEmail:'',
                user_type:1,
                list:'',
                btcaction: 0,
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
            this.fetchuserlist(this.pagination.current_page);
            this.interval = setInterval(function () {
                this.fetchuserlist(this.pagination.current_page);
            }.bind(this), 8000);
        },
        methods:
            {


                fetchuserlist(page)
                {
                    axios.get('api/user_list?page='+page+'&search='+this.searechByEmail).then((res) => {
                        this.list={};
                        this.list = res.data.data;
                        this.list_count= 1;
                        this.pagination=res.data.pagination
                    });
                },
                getRemaining() {
                    axios.get('api/user_list?page=${this.list.current_page}&search='+this.searechByEmail)
                        .then((response) => {
                            this.list={};
                            this.list = res.data.data;
                        })
                        .catch(() => {
                            console.log('handle server error from here');
                        });
                },
                searchEmail(){
                    axios.get('api/user_list?page=${this.list.current_page}&search='+this.searechByEmail)
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
                    this.fetchuserlist(page);
                },

                whitelisted(id,type){
                    axios.get('api/whitelisted?ip='+id+'&type='+type)
                        .then((response) => {

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
