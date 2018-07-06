<template>
    <div class="animated fadeIn">
        <div v-if="user_type == 1" class="row container">
            <div class="col-12">
                <h2>Access List</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search By Name..." style="height:40px;" name="search_ip" v-model="searchByName">
                        </div>
                        <table class="table table-responsive-sm table-sm">
                            <thead>
                            <th>Id</th>
                            <th>User Name</th>
                            <th>Country</th>
                            <th>Coin Type</th>
                            <th>Coin Amount</th>
                            <th>Created At</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <tr v-for="(listdata, indexs) in  list.data">
                                <td> {{ indexs +1 }} </td>
                                <td> {{ listdata.user.name }} </td>
                                <td> {{ listdata.country}} </td>
                                <td> {{ listdata.coin_type}} </td>
                                <td> {{ listdata.coin_amount}} </td>
                                <td> {{ listdata.created_at}} </td>
                                <td v-if="listdata.active == 1"> <button type="button" v-on:click="accessStatus(listdata.id,0)" class="btn btn-danger">Click To Inactive</button>  </td>
                                <td v-if="listdata.active == 0"> <button type="button" v-on:click="accessStatus(listdata.id,1)" class="btn btn-success">Click To Active</button>  </td>
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
                        <nav>
                            <ul class="pagination">
                                <li v-if="pagination.current_page > 1">
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
                                <li v-if="pagination.current_page < pagination.last_page">
                                    <a href="#" aria-label="Next"
                                       @click.prevent="changePage(pagination.current_page + 1)">
                                        <span aria-hidden="true">»</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="user_type == 0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-danger">
                            <h3 class="text-center">You are not authorized to access this page.</h3>
                        </div>
                        <p> Go to Dashboard.
                            <router-link to="/markets">Click Here.</router-link>
                        </p>
                        <div class="text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        name: 'AccessList',

        data()
        {
            return {
                searchByName:'',
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
            this.fetchAccessList(this.pagination.current_page);
            this.interval = setInterval(function () {
                this.fetchAccessList(this.pagination.current_page);
            }.bind(this), 8000);
        },
        methods:
            {
                fetchAccessList(page)
                {
                    axios.get('api/access?page='+page+'&search='+this.searchByName).then((res) => {
                        this.list={};
                        this.list = res.data.data;
                        this.list_count= 1;
                        this.pagination=res.data.pagination
                    });
                },
                getRemaining() {
                    axios.get('api/access?page=${this.list.current_page}&search='+this.searchByName)
                        .then((response) => {
                            this.list={};
                            this.list = res.data.data;
                        })
                        .catch(() => {
                            console.log('handle server error from here');
                        });
                },
                searchName(){
                    axios.get('api/access?page=${this.list.current_page}&search='+this.searchByName)
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
                    this.fetchAccessList(page);
                },
                accessStatus(id,type){
                    axios.get('api/access-status?id='+id+'&type='+type+'&search='+this.searchByName)
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