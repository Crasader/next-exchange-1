@extends('_layouts.admin') 
@section('template_title') Create New User
@endsection
 
@section('template_fastload_css')
@endsection


@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create New User

                    <a href="/users" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply text-white" aria-hidden="true"></i>
              Back <span class="hidden-xs">to</span><span class="hidden-xs"> Users</span>
            </a>

                </div>
                <div class="panel-body">

                    {!! Form::open(array('action' => 'UsersManagementController@store')) !!}

                    <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                        {!! Form::label('name', 'Username', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'Username')) !!} {{--
                                <label
                                    class="input-group-addon" for="name"><i class="fa fa-fw {{ trans('forms.create_user_icon_username') }}" aria-hidden="true"></i></label>
                                    --}}
                            </div>
                            @if ($errors->has('name'))
                            <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span> @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                        {!! Form::label('email', 'Email', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('email', NULL, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email')) !!} {{--
                                <label
                                    class="input-group-addon" for="email"><i class="fa fa-fw {{ trans('forms.create_user_icon_email') }}" aria-hidden="true"></i></label>
                                    --}}
                            </div>
                            @if ($errors->has('email'))
                            <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span> @endif
                        </div>
                    </div>
                    <div class="form-group has-feedback row {{ $errors->has('phone_country_code') ? ' has-error ' : '' }}">
                        {!! Form::label('phone_country_code', 'Country Code', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="form-control js-example-basic-single" name="phone_country_code" id="phone_country_code">
                                <option value="">Country Code</option>
                                @if (count($country_codes))
                                    @foreach($country_codes as $country_code)
                                        <option value="{{ $country_code['value'] }}">{{ $country_code['label'] }}</option>
                                    @endforeach
                                @endif
                                </select> {{-- <label class="input-group-addon" for="name"><i class="fa fa-fw {{ trans('forms.create_user_icon_role') }}" aria-hidden="true"></i></label>                                --}}
                            </div>
                            @if ($errors->has('role'))
                            <span class="help-block">
                        <strong>{{ $errors->first('role') }}</strong>
                    </span> @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('phone_number') ? ' has-error ' : '' }}">
                        {!! Form::label('phone_number', 'Phone Number', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::number('phone_number', NULL ,array('id' => 'phone_number', 'class' => 'form-control ', 'placeholder' => 'Phone Number')) !!} {{-- <label class="input-group-addon" for="phone_number"><i class="fa fa-fw {{ trans('forms.create_user_icon_phone_number') }}" aria-hidden="true"></i></label>                                --}}
                            </div>
                            @if ($errors->has('phone_number'))
                            <span class="help-block">
                          <strong>{{ $errors->first('phone_number') }}</strong>
                      </span> @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                        {!! Form::label('role', 'User Role', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="form-control js-example-basic-single" name="role" id="role">
                                    <option value="">Choose a Role</option>
                                    @if ($roles->count())
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                </select> {{-- <label class="input-group-addon" for="name"><i class="fa fa-fw {{ trans('forms.create_user_icon_role') }}" aria-hidden="true"></i></label>                                --}}
                            </div>
                            @if ($errors->has('role'))
                            <span class="help-block">
                        <strong>{{ $errors->first('role') }}</strong>
                    </span> @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('password') ? ' has-error ' : '' }}">
                        {!! Form::label('password', 'Password', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => 'Enter a secure password'))
                                !!} {{-- <label class="input-group-addon" for="password"><i class="fa fa-fw {{ trans('forms.create_user_icon_password') }}" aria-hidden="true"></i></label>                                --}}
                            </div>
                            @if ($errors->has('password'))
                            <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span> @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('password_confirmation') ? ' has-error ' : '' }}">
                        {!! Form::label('password_confirmation', 'Confirm Password', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder'
                                => 'Confirm Password')) !!} {{-- <label class="input-group-addon"
                                    for="password_confirmation"><i class="fa fa-fw {{ trans('forms.create_user_icon_pw_confirmation') }}" aria-hidden="true"></i></label>                                --}}
                            </div>
                            @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span> @endif
                        </div>
                    </div>

                    {!! Form::button('<i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;' .'Create User', array('class'
                    => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', )) !!} {!! Form::close()
                    !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('footer_scripts')
 <script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });  
</script>
@endsection