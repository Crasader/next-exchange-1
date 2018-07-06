@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.flashalert')

    <style>
        #profile h3 { color: #252525;
            font-weight:400; padding-left: 15px; padding-bottom: 15px; }
        .input-group-addon { margin-right: 10px; background-color: #fff; }

        #msg { background-color: #00b3ee; color: #0a3544; padding: 20px; border: 1px solid #0a3544; font-size: 16px; }
    </style>

    <section id="profile" class="ptb40 bg--secondary">



                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                    @endif


                                <!-- ============================================================== -->
                                <!-- Container fluid  -->
                                <!-- ============================================================== -->
                                <div class="container">
                                    <!-- ============================================================== -->
                                    <!-- Bread crumb and right sidebar toggle -->
                                    <!-- ============================================================== -->
                                  <!--
                                    <div class="row page-titles">

                                        <div class="col-md-7 align-self-center">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                                <li class="breadcrumb-item">Dashboard</li>
                                                <li class="breadcrumb-item active">Profile</li>
                                            </ol>
                                        </div>

                                    </div>
                                    -->
                                    <!-- ============================================================== -->
                                    <!-- End Bread crumb and right sidebar toggle -->
                                    <!-- ============================================================== -->

                                    <!-- ============================================================== -->
                                    <!-- Start Page Content -->
                                    <!-- ============================================================== -->
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <div id="msg" style="display: none"></div>

                                                {!! Form::open(['route' => 'profile_store', 'id'=>'update_profile', 'class' => 'form-horizontal']) !!}

                                                 <meta name="_token" content="{{ csrf_token() }}" />


                                                    {{-- Form::open(['url' =>('/upload1'), 'class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) --}}

                                                <!--
                                                    <div class="dz-message">

                                                    </div>

                                                    <div class="fallback">
                                                        <input name="file" type="file" multiple />
                                                    </div>

                                                    <div class="dropzone-previews" id="dropzonePreview"></div>

                                                    <h4 style="text-align: center;color:#428bca;">Drop images in this area  <span class="glyphicon glyphicon-hand-down"></span></h4>
                                                    -->


                                                    {{-- Form::close() --}}

                                                <section class="c-graph-card ptb40">
                                                    <h3>Profile information</h3>
                                                    <div class="form-group ">
                                                        <label class="col-md-4 control-label" for="Name (Full name)">Name (Full name)</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-user">
                                                                    </i>
                                                                </div>
                                                                <input id="fullname" name="fullname" type="text" placeholder="Name (Full name)" class="form-control input-md" value="<?= $user->fullname ?>">
                                                            </div>


                                                        </div>


                                                    </div>

                                                    <!-- File Button -->

                                                    <!-- Text input-->



                                                    <!-- Text input-->
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Father">Occupation</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-laptop" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="occupation" name="occupation" type="text" placeholder="Occupation" class="form-control input-md" value="<?= $user->occupation?>">

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Text input-->
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Company Name</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-building-o" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="company" name="company" type="text" placeholder="company" class="form-control input-md" value="<?= $user->company?>">

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Phone No</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-phone" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="phone" name="phone" type="text" placeholder="phone" class="form-control input-md" value="<?= $user->phone?>">

                                                            </div>

                                                        </div>
                                                    </div>

                                                </section>

                                                <section class="c-graph-card ptb40">
                                                    <h3>Address information</h3>

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Address</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-location-arrow" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="address" name="address" type="text" placeholder="address" class="form-control input-md" value="<?= $user->address ?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">City</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-location-arrow" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="city" name="city" type="text" placeholder="city" class="form-control input-md" value="<?= $user->city?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">State</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-location-arrow" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="state" name="state" type="text" placeholder="state" class="form-control input-md" value="<?= $user->state?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Post Code</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-location-arrow" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="postcode" name="postcode" type="text" placeholder="Post Code" class="form-control input-md" value="<?= $user->postcode?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Country</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-location-arrow" style="font-size: 20px;"></i>

                                                                </div>
                                                                @include('_partials.countrylist', ['default' => $user->country ])

                                                            </div>

                                                        </div>
                                                    </div>



                                                </section>

                                                <section class="c-graph-card ptb40">

                                                    <h3>Crypto information</h3>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Bitcoin</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-btc" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="bitcoin" name="bitcoin" type="text" placeholder="" class="form-control input-md" value="<?= $user->bitcoin ?>">

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Litecoin</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-btc" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="litecoin" name="litecoin" type="text" placeholder="" class="form-control input-md" value="<?= $user->litecoin ?>">

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Ether</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-btc" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="ether" name="ether" type="text" placeholder="" class="form-control input-md" value="<?= $user->ether ?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                </section>


                                                <section class="c-graph-card ptb40">
                                                    <h3>Social Links</h3>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Facebook</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-facebook-square" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="facebook" name="facebook" type="text" placeholder="" class="form-control input-md" value="<?= $user->facebook?>">

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Linkedin</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-linkedin-square" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="linkedin" name="linkedin" type="text" placeholder="" class="form-control input-md" value="<?= $user->linkedin?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Twitter</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-twitter-square" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="twitter" name="twitter" type="text" placeholder="" class="form-control input-md" value="<?= $user->twitter?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label" for="Mother">Instagram</label>
                                                        <div class="col-md-4">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-instagram" style="font-size: 20px;"></i>

                                                                </div>
                                                                <input id="instagram" name="instagram" type="text" placeholder="" class="form-control input-md" value="<?= $user->instagram ?>">

                                                            </div>

                                                        </div>
                                                    </div>
                                                </section>


                                                <input type="submit" class="c-btn" value="Update information" id="update" />


                                            {!! Form::close() !!}



                                            </div>
                                    </div>

                                </div>
                            </div>



    </section>

            <script type="text/javascript">

                $(function() {

                    $('#update_profile').on('submit',function(e){

                        $.ajaxSetup({
                            header:$('meta[name="_token"]').attr('content')
                        });
                        e.preventDefault(e);

                        $.ajax({
                            url: '/profile/update',
                            type: 'POST',
                            data: $(this).serialize(),
                            dataType: 'json',
                            success: function(data){
                                if(data.success == true ){
                                    $('#update_profile').remove();
                                    $('#msg').show();
                                    $('#msg').html('Profile Update Successfully');
                                } else {
                                    $('#msg').show();
                                    $('#msg').html('Profile Update NOT updated, please try again');
                                }
                            },
                            error: function(){},
                        });""
                    });
                });
                setTimeout(function() {
                    //$('#msg').fadeOut('fast');
                }, 20000);




            </script>

@endsection
