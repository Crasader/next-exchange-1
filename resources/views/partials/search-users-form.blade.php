<div class="row">
    <div class="col-lg-offset-8 col-xl-4\">
        {!! Form::open(['route' => 'search-users', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation', 'id' => 'search_users']) !!}
            {!! csrf_field() !!}
            <div class="input-group margin-bottom-2">
                {!! Form::text('user_search_box', NULL, ['id' => 'user_search_box', 'class' => 'form-control', 'placeholder' => trans('Search Users'), 'aria-label' => trans('usersmanagement.search.search-users-ph'), 'required' => false]) !!}
                <a href="#" class="input-group-addon btn btn-warning clear-search" data-toggle="tooltip" title="@lang('lusersmanagement.tooltips.clear-search')" style="display:none;">
                    <i class="fa fa-times" aria-hidden="true"></i>
                    <span class="sr-only">
                        @lang('lusersmanagement.tooltips.clear-search')
                    </span>
                </a>
                <a href="#" class="input-group-addon btn" id="search_trigger">
                    <i class="fa fa-search fa-fw" aria-hidden="true"></i>
                    <span class="sr-only">
                        {{  trans('usersmanagement.tooltips.submit-search') }}
                    </span>
                </a>
            </div>
        {!! Form::close() !!}
    </div>
</div>
