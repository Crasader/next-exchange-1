<template>
  <div class="animated fadeIn">
    <div class="card-group mb-4">
      <div class="card">
      <div class="card-header">
        Profile information
		</div>
		<div id='msg' class='alert alert-success' v-if="success==true">
		<strong>{{message}}!</strong> 
		</div>
		<div class="card-body"> 
			<form class="col-sm-12" role="form" v-on:submit.prevent=Savedetails()>
				 <section class="c-graph-card ptb40">
					 <h3>Profile information</h3>
					 <div class="form-group ">
						<label class="col-md-4 control-label" for="Name (Full name)">Name (Full name)</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-user"></i>
								</div>
								<input id="fullname" name="fullname" type="text" placeholder="Name (Full name)" class="form-control input-md" v-model="user.fullname" required>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Father">Occupation</label>
							<div class="col-md-8">
								<div class="input-group">
									 <div class="input-group-addon">
										<i class="fa fa-laptop" style="font-size: 20px;"></i>
									</div>
									<input id="occupation" name="occupation" type="text" placeholder="Occupation" class="form-control input-md" v-model="user.occupation" required>
								</div>
							</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Company Name</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-building-o" style="font-size: 20px;"></i>
								</div>
								<input id="company" name="company" type="text" placeholder="company" class="form-control input-md" v-model="user.company">
								</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Phone No</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
								<i class="fa fa-phone" style="font-size: 20px;"></i>
								</div>
								<input id="phone" name="phone" type="text" placeholder="phone" class="form-control input-md" v-model="user.phone" required>
							</div>
						</div>
					</div>
				</section>
				<section class="c-graph-card ptb40">
					<h3>Address information</h3>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Address</label>
						<div class="col-md-8">
								<div class="input-group">
									<div class="input-group-addon">
									<i class="fa fa-location-arrow" style="font-size: 20px;"></i>
									</div>
									<input id="address" name="address" type="text" placeholder="address" class="form-control input-md" v-model="user.address" required> 
								</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">City</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-location-arrow" style="font-size: 20px;"></i>
								</div>
								<input id="city" name="city" type="text" placeholder="city" class="form-control input-md" v-model="user.city">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">State</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-location-arrow" style="font-size: 20px;"></i>
								</div>
								<input id="state" name="state" type="text" placeholder="state" class="form-control input-md" v-model="user.state">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Post Code</label>
							<div class="col-md-8">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-location-arrow" style="font-size: 20px;"></i>
									</div>
									<input id="postcode" name="postcode" type="text" placeholder="Post Code" class="form-control input-md" v-model="user.postcode">
								</div>
							</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Country</label>
						<div class="col-md-8">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-location-arrow" style="font-size: 20px;"></i>
									</div>
									<select class="form-control" name="country" v-model="user.country" >
									<option  v-for="state in statelist" :value="state.code">{{state.name}}</option>
									</select>
								</div>
						</div>
					</div>
				</section>
				<section class="c-graph-card ptb40">
				<h3>Crypto information</h3>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Bitcoin</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-btc" style="font-size: 20px;"></i>
								</div>
								<input id="bitcoin" name="bitcoin" type="text" placeholder="" class="form-control input-md" v-model="user.bitcoin">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Litecoin</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-btc" style="font-size: 20px;"></i>
								</div>
								<input id="litecoin" name="litecoin" type="text" placeholder="" class="form-control input-md" v-model="user.litecoin">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Ether</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
								<i class="fa fa-btc" style="font-size: 20px;"></i>
							</div>
							<input id="ether" name="ether" type="text" placeholder="" class="form-control input-md" v-model="user.ether">
							</div>
						</div>
					</div>
				</section>
				<section class="c-graph-card ptb40">
					<h3>Social Links</h3>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Facebook</label>
							<div class="col-md-8">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-facebook-square" style="font-size: 20px;"></i>
									</div>
									<input id="facebook" name="facebook" type="text" placeholder="" class="form-control input-md" v-model="user.facebook">
								</div>
							</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Linkedin</label>
							<div class="col-md-8">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-linkedin-square" style="font-size: 20px;"></i>
									</div>
									<input id="linkedin" name="linkedin" type="text" placeholder="" class="form-control input-md" v-model="user.linkedin">
								</div>
							</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Twitter</label>
						<div class="col-md-8">
							<div class="input-group">
								<div class="input-group-addon">
								<i class="fa fa-twitter-square" style="font-size: 20px;"></i>
								</div>
								<input id="twitter" name="twitter" type="text" placeholder="" class="form-control input-md" v-model="user.twitter">
							</div>
						
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label" for="Mother">Instagram</label>
							<div class="col-md-8">
								<div class="input-group">
									<div class="input-group-addon">
									<i class="fa fa-instagram" style="font-size: 20px;"></i>
								</div>
								<input id="instagram" name="instagram" type="text" placeholder="" class="form-control input-md" v-model="user.instagram">
								</div>
						
							</div>
					</div>
					<div class="form-group">
					<input type="submit" class="c-btn" value="Update information">
					</div>
				</section>
			</form>
		</div>
      </div>
    </div>
  </div>
</template>
<script>
import States from '../country.js'
import { Validator } from 'vee-validate'
export default {
  name: 'profile',
   data() {
            return{
                message:'',
                success:'false',
                user:{
                    fullname:'',
                    occupation:'',
                    company:'',
                    phone:'',
                    address:'',
                    city:'',
                    state:'',
                    postcode:'',
                    country:'',
                    bitcoin:'',
				 	litecoin:'',
					ether:'',
					facebook:'',
					linkedin:'',
					twitter:'',
					instagram:''
                },
                statelist:States.STATES,
                              
            }
        },
        mounted ()
        {
			this.Getdetails()
            
        },
        methods:{
			Getdetails(){
				var url='/getdetails';
				this.$http.get(url).then(response => {	
				    
					if(response.data.success==1)
					{
					this.user=response.data.data[0];
					}
					
				 });
		
		
			},
			
			Savedetails()
			{
				   
                    var url ='/update';
					this.$http.post(url,this.user).then(response => {
					if(response.data.success==true)
					{
				    this.message = 'Profile Update Successfully.'; 
					this.success=true;
					}
				
				  
					   
					
				 });
			
			}
         
        }
}
 </script>  

