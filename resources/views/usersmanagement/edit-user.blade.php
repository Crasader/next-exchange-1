@extends('_layouts.admin') 
@section('template_title') Editing User {{ $user->name }}
@endsection
 
@section('template_linked_css')
<style type="text/css">
    .btn-save,
    .pw-change-container {
        display: none;
    }
</style>
@endsection
 
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <strong>Editing User:</strong> {{ $user->name }}

                    <a href="/users/{{$user->id}}" class="btn-primary btn-sm pull-right" style="margin-left: 1em;">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
             Back  <span class="hidden-xs">to User</span>
            </a>

                    <a href="/users" class="btn-success btn-sm pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Back to </span>Users
            </a>

                </div>

                {!! Form::model($user, array('action' => array('UsersManagementController@update', $user->id), 'method' => 'PUT')) !!} {!!
                csrf_field() !!}

                <div class="panel-body">

                    <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                        {!! Form::label('name', 'Username' , array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('Username')))
                                !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
                        {!! Form::label('email', 'E-mail' , array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('email', old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => trans('Email')))
                                !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('phone_country_code') ? ' has-error ' : '' }}">
                        {!! Form::label('phone_country_code','Country Code', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="form-control js-example-basic-single" name="phone_country_code" id="phone_country_code">
                                    <option value="">Country Code</option>
                                    @if (count($country_codes))
                                    @foreach($country_codes as $country_code)
                                        <option value="{{ $country_code['value'] }}" {{ $currentCountryCode == $country_code['value'] ? 'selected="selected"' : '' }}>{{ $country_code['label'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('phone_number') ? ' has-error ' : '' }}">
                        {!! Form::label('phone_number', 'Phone Number' , array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('phone_number', old('phone_number'), array('id' => 'phone_number', 'class' => 'form-control', 'placeholder'
                                => trans('Phone Number'))) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                        {!! Form::label('role','User Role', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="form-control js-example-basic-single" name="role" id="role">
                                <option value="">Choose a Role</option>
                                    @if ($roles->count())
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $currentRole[0]->id === $role->id ? 'selected="selected"' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @if ($errors->has('role'))
                            <span class="help-block">
                        <strong>{{ $errors->first('role') }}</strong>
                    </span> @endif
                        </div>
                    </div>

                    <div class="pw-change-container">
                        <div class="form-group has-feedback row">
                            {!! Form::label('password', 'Password', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => 'Password')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('password_confirmation', 'Confirm Password', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder'
                                    => 'Confirm Password')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="panel-footer">

                    <div class="row">

                        <div class="col-xs-6">
                            <a href="#" class="btn btn-default btn-block margin-bottom-1 btn-change-pw" title="Change Password">
                    <i class="fa fa-fw fa-lock" aria-hidden="true"></i>
                    <span></span> Change Password
                  </a>
                        </div>
                        <div class="col-xs-6">
                            {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class'
                            => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' =>
                            'modal', 'data-target' => '#confirmSave', 'data-title' => 'Edit User', 'data-message' => 'Save
                            Changes?')) !!}
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
    @include('modals.modal-save')
    @include('modals.modal-delete')
@endsection
 
@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.check-changed')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });  
    </script>
@endsection