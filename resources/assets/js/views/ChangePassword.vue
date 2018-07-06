<template>
    <div class="animated fadeIn">
        <div class="card-group mb-4" v-if="user_type == 1">
            <div class="card">
                <div class="card-header">
                    Change Password
                </div>
                <div id='msg' class='alert alert-success' v-if="success==true">
                    <strong>{{message}}!</strong>
                </div>
                <div class="card-body">
                    <form class="col-sm-12" role="form" v-on:submit.prevent=Savedetails()>
                        <section class="c-graph-card ptb40">
                            <h3>Change Password.</h3>
                            <div class="form-group ">
                                <label class="col-md-4 control-label" for="old_password">Old Password</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input id="fullname" name="old_password" type="password" placeholder="Old Password" class="form-control input-md" v-model="user.old_password"  >
                                    </div>
                                    <p style="color:red;">{{ error_old_password }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="new_password">New Password.</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input id="new_password" name="new_password" type="password" placeholder="New Password" class="form-control input-md" v-model="user.new_password"  >
                                    </div>
                                    <p style="color:red;" >{{ error_new_password }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="confirm_password">Re Enter Password</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input id="re_password" name="confirm_password" type="password" placeholder="Re Enter Password" class="form-control input-md"  v-model="user.confirm_password">
                                    </div>
                                    <p style="color:red;" >{{ error_confirm_password }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="c-btn" name="submit" value="Update" id="update_password" >
                            </div>
                        </section>
                    </form>
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
<script>/* manglia.indore@bankofindia.co.in*/
import { Validator } from 'vee-validate'
export default {
    name: 'profile',
    data() {
        return{
            message:'',
            success:'false',
            user_type:1,
            error_confirm_password:'',
            error_new_password:'',
            error_old_password:'',
            user:{
                old_password:'',
                new_password:'',
                confirm_password:'',

            },

        }
    },
    mounted ()
    {


    },
    methods:{
        Savedetails()
        {
            console.log(this.user);
            var url ='/api/change_password';
            this.$http.post(url,this.user).then(response => {
                if(response.data.success==true)
                {
                    this.message = 'Password Update Successfully.';
                    this.success=true;
                }else{


                    this.error_confirm_password = (response.data.message && response.data.message.confirm_password && response.data.message.confirm_password[0]) ? response.data.message.confirm_password[0] : null;

                    this.error_new_password = (response.data.message && response.data.message.new_password && response.data.message.new_password[0]) ? response.data.message.new_password[0] : null;

                    this.error_old_password = (response.data.message && response.data.message.old_password && response.data.message.old_password[0]) ? response.data.message.old_password[0] : null;

                }
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

