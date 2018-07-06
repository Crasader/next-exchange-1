{{-- 
@extends('_layouts.main') 
@section('content')
    @include('_layouts.topnav')
<div class="container mt-5">

    <link rel="stylesheet" href="{{asset('css/admin.css')}}" type="text/css" />
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                                @lang('Users List')
                            </span>

                        <div class="btn-group pull-right btn-group-xs">

                            <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v fa-fw" aria-hidden="true"></i>
                                    <span class="sr-only">
                                        Show Users Management Menu
                                    </span>
                                </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/users/create">
                                            <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                            Create New User
                                        </a>
                                </li>
                                <li>
                                    <a href="/users/deleted">
                                            <i class="fa fa-fw fa-group" aria-hidden="true"></i>
                                            Show Deleted User
                                        </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
    @include('partials.search-users-form')

                    <div class="table-responsive users-table">
                        <table class="table table-striped table-condensed data-table">
                            <thead class="thead">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th class="hidden-xs">Email</th>
                                    <th>Role</th>
                                    <th class="hidden-sm hidden-xs hidden-md">Created</th>
                                    <th class="hidden-sm hidden-xs hidden-md">Updated</th>
                                    <th>Actions</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="users_table">
                                @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td class="hidden-xs"><a href="mailto:{{ $user->email }}" title="email {{ $user->email }}">{{ $user->email }}</a></td>
                                    <td>
                                        @foreach ($user->roles as $user_role) @if ($user_role->name == 'User') @php $labelClass = 'primary' 
@endphp @elseif ($user_role->name
                                        == 'Admin') @php $labelClass = 'warning' 
@endphp @elseif ($user_role->name == 'Unverified')
                                        @php $labelClass = 'danger' 
@endphp @else @php $labelClass = 'default' 
@endphp @endif

                                        <span class="label label-{{$labelClass}}">{{ $user_role->name }}</span> @endforeach
                                    </td>
                                    <td class="hidden-sm hidden-xs hidden-md">{{$user->created_at}}</td>
                                    <td class="hidden-sm hidden-xs hidden-md">{{$user->updated_at}}</td>
                                    <td>
                                        {!! Form::open(array('url' => 'users/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!} {!! Form::button('
                                        <i
                                            class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"> User</span>',
                                            array('class' => 'btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;'
                                            ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' =>
                                            'Delete User', 'data-message' => 'Are you sure you want to delete this user ?'))
                                            !!} {!! Form::close() !!}
                                    </td>
                                    <td>
                                        <a class="btn-sm btn-success btn-block" href="{{ URL::to('users/' . $user->id) }}" data-toggle="tooltip" title="Show">
                                                    <i class="fa fa-eye fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Show</span><span class="hidden-xs hidden-sm hidden-md"> User</span>
                                                </a>
                                    </td>
                                    <td>
                                        <a class="btn-sm btn-info btn-block" href="{{ URL::to('users/' . $user->id . '/edit') }}" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-pencil fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Edit</span><span class="hidden-xs hidden-sm hidden-md"> User</span>
                                                </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tbody id="search_results"></tbody>
                        </table>

                        <span id="user_count"></span>
                        <span id="user_pagination">
                                {{ $users->links() }}
                            </span>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('modals.modal-delete')
@endsection
 
@section('footer_scripts')
<script src="{{ asset('/js/admin.js') }}"></script>

@if(config('settings.googleMapsAPIStatus')) {!! HTML::script('//maps.googleapis.com/maps/api/js?key='.config("settings.googleMapsAPIKey").'&libraries=places&dummy=.js',
array('type' => 'text/javascript')) !!} @endif
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.search-users')
@endsection
 --}} 
@extends('_layouts.admin') 
@section('template_title') Showing Users
@endsection


@section('template_linked_css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<style type="text/css" media="screen">
    .users-table {
        border: 0;
    }

    .users-table tr td:first-child {
        padding-left: 15px;
    }

    .users-table tr td:last-child {
        padding-right: 15px;
    }

    .users-table.table-responsive,
    .users-table.table-responsive table {
        margin-bottom: 0;
    }
</style>
@endsection
 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                                @lang('Users List')
                            </span>

                        <div class="btn-group pull-right btn-group-xs">

                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer">
                                    <i class="fa fa-ellipsis-v fa-fw" aria-hidden="true"></i>
                                    <span class="sr-only">
                                        Show Users Management Menu
                                    </span>
                                </a>

                            <ul class="dropdown-menu option">
                                <li>
                                    <a href="/users/create">
                                            <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                            Create New User
                                        </a>
                                </li>
                                <li>
                                    <a href="/users/deleted">
                                            <i class="fa fa-fw fa-group" aria-hidden="true"></i>
                                            Show Deleted User
                                        </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
    @include('partials.search-users-form')

                    <div class="table-responsive users-table">
                        <table class="table table-striped table-condensed data-table">
                            <thead class="thead">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th class="hidden-xs">Email</th>
                                    <th>Role</th>
                                    <th class="hidden-sm hidden-xs hidden-md">Created</th>
                                    <th class="hidden-sm hidden-xs hidden-md">Updated</th>
                                    <th>Actions</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="users_table">
                                @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td class="hidden-xs"><a href="mailto:{{ $user->email }}" title="email {{ $user->email }}">{{ $user->email }}</a></td>
                                    <td>
                                        @foreach ($user->roles as $user_role) @if ($user_role->name == 'User') @php $labelClass = 'primary' 
@endphp @elseif ($user_role->name
                                        == 'Admin') @php $labelClass = 'warning' 
@endphp @elseif ($user_role->name == 'Unverified')
                                        @php $labelClass = 'danger' 
@endphp @else @php $labelClass = 'default' 
@endphp @endif

                                        <span class="label label-{{$labelClass}}">{{ $user_role->name }}</span> @endforeach
                                    </td>
                                    <td class="hidden-sm hidden-xs hidden-md">{{$user->created_at}}</td>
                                    <td class="hidden-sm hidden-xs hidden-md">{{$user->updated_at}}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success btn-block" href="{{ URL::to('users/' . $user->id) }}" data-toggle="tooltip" title="Show">
                                                    <span class="hidden-xs hidden-sm">Show</span><span class="hidden-xs hidden-sm hidden-md"> User</span>
                                                </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-info btn-block" href="{{ URL::to('users/' . $user->id . '/edit') }}" data-toggle="tooltip" title="Edit">
                                                    <span class="hidden-xs hidden-sm">Edit</span><span class="hidden-xs hidden-sm hidden-md"> User</span>
                                                </a>
                                    </td>
                                    <td>
                                        {!! Form::open(array('url' => 'users/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!} {!! Form::button('
                                        <span
                                            class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"> User</span>', array('class'
                                            => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle'
                                            => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User',
                                            'data-message' => 'Are you sure you want to delete this user ?')) !!} {!! Form::close()
                                            !!}

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tbody id="search_results"></tbody>
                        </table>

                        <span id="user_count"></span>
                        <span id="user_pagination">
                                {{ $users->links() }}
                            </span>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('modals.modal-delete')
@endsection
 
@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.search-users')
@endsection