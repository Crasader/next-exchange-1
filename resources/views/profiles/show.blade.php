@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.flashalert')

    <section class="bg--secondary space--sm">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="boxed boxed--lg boxed--border">
                        <div class="text-block text-center">
                            <img class="image--md" src="{{ Gravatar::get($data['user']->email) }}" alt="{{ $data['user']->name }}">
                            <span class="h5">{{ $data['user']->first_name}}&nbsp;{{ $data['user']->last_name}}</span>
                            <span>{{ $data['user']->about_me }}</span>
                            <span class="label">{{ $data['user']->title }}</span>
                        </div>
                        <div class="text-block clearfix text-center">
                            <ul class="row row--list">
                                <li class="col-sm-4">
                                    <span class="type--fine-print block">Location:</span>
                                    <span>{{ $data['user']->location}}&nbsp;</span>
                                    <img alt="Image" class="flag" src="img/flag-3.png">
                                </li>
                                <li class="col-sm-4">
                                    <span class="type--fine-print block">Member Since:</span>
                                    <span>{{ $data['user']->member_since }}</span>
                                </li>
                                <li class="col-sm-4">
                                    <span class="type--fine-print block">Contact:</span>
                                    <a href="#">{{ $data['user']->email}}</a>
                                </li>
                        </div>
                        </ul>
                    </div>
                    <div class="boxed boxed--border">
                        <ul class="row row--list clearfix text-center">
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Likes</span>
                                <span class="h3">{{ $data['user']->likes}}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Articles</span>
                                <span class="h3">{{ $data['user']->articles}}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Comments</span>
                                <span class="h3">{{ $data['user']->comments}}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Following</span>
                                <span class="h3">{{ $data['user']->following}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="boxed boxed--border">
                        <h4>Connections</h4>
                        @if ($data['user']->connections <> 0)
                            <ul class="clearfix row row--list text-center">
                                @foreach($data['user']->connections as $connection)
                                    <li class="col-sm-3 col-xs-6">
                                        <a href="{{ url('profile', $connection->name) }}">
                                            <img alt="avatar" src="/img/avatar-round-2.png" class="image--sm" />
                                            <span class="block">{{ $connection->first_name }}&nbsp;{{ $connection->last_name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="#" class="type--fine-print pull-right">View All</a>
                        @else
                            No Connections found.
                        @endif
                    </div>
                    <div class="boxed boxed--border">
                        <h4>Recent Activity</h4>
                        <ul>
                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-2 col-xs-3 text-center">
                                        <div class="icon-circle">
                                            <i class="icon icon--lg material-icons">mode_edit</i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xs-7">
                                        <span class="type--fine-print">21st August, 2017</span>
                                        <a href="#" class="block color--primary">Making the whitepaper</a>
                                        <p>
                                            Making the CorporateFinanceHouse white-paper for the ICO launch in September.
                                        </p>
                                    </div>
                                </div>
                                <hr>
                            </li>
                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-2 col-xs-3 text-center">
                                        <div class="icon-circle">
                                            <i class="icon icon--lg material-icons">comment</i>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-xs-7">
                                        <span class="type--fine-print">14th August, 2017</span>
                                        <a href="#" class="block color--primary">New website!</a>
                                        <p>
                                            Launched the new website and ico landing page at <a href="https://ico.corporatefinancehouse.com">https://ico.corporatefinancehouse.com</a>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                            </li>
                        </ul>
                        <a href="#" class="type--fine-print pull-right">View All</a>
                    </div>
                </div>
            </div>
            <!--end of row-->
        </div>
        <!--end of container-->
    </section>
@endsection
