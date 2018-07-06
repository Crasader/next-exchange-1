@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.flashalert')

    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-md-offset-2 ">
                    <div class="boxed boxed--lg boxed--border bg-white">
                        <div class="text-block text-center">
                            <img class="image--md" src="{{ Gravatar::get($user->email) }}" alt="{{ $user->name }}">
                            <br>
                            <span class="h5">{{ $user->name }}</span>
                            <br>
                            <span>{{ $user->about_me }}</span>
                            <span class="label">{{ $user->title }}</span>
                        </div>
                        @if($user->id !== Auth::id())
                        <div class="text-center">
                            <div class="btn-block">
                                @if($isLiked)
                                    <button class="btn btn--small btn--outline profile-like" data-value="0">Unlike</button>
                                @else
                                    <button class="btn btn--small btn--outline profile-like" data-value="1">Like</button>
                                @endif

                                @if($isFollowing)
                                    <button class="btn btn--small btn--outline profile-follow" data-value="0">Unfollow</button>
                                @else
                                    <button class="btn btn--small btn--outline profile-follow" data-value="1">Follow</button>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="text-block clearfix text-center">
                            <ul class="row row--list">
                                <li class="col-sm-4">
                                    <span class="type--fine-print block"><b>Location</b></span>
                                    <span>{{ $user->location }}&nbsp;</span>

                                </li>
                                <li class="col-sm-4">
                                    <span class="type--fine-print block"><b>Member Since</b></span>
                                    <span>{{ $user->member_since }}</span>
                                </li>
                                <li class="col-sm-4">
                                    <span class="type--fine-print block"><b>Contact</b></span>

                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="boxed boxed--border bg-white">
                        <ul class="row row--list clearfix text-center">
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Likes</span>
                                <span class="h3">{{ $user->likes_count }}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Articles</span>
                                <span class="h3">{{ $user->articles_count }}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Projects</span>
                                <span class="h3">{{ $user->projects_count }}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Following</span>
                                <span class="h3">{{ $user->followers_count }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="boxed boxed--border bg-white">
                        <h4>Connections</h4>
                        @if ($user->connections->count() > 0)
                            <ul class="clearfix row row--list text-center">
                                @foreach($user->connections as $connection)
                                    <li class="col-sm-3 col-xs-6">
                                        <a href="{{ url('profile', $connection->name) }}">
                                            <img alt="avatar" src="/img/default-avatar.png" class="image--sm" />
                                            <span class="block">{{ $connection->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="#" class="type--fine-print pull-right">View All</a>
                        @else
                            No connections found
                        @endif
                    </div>

                    <!--
                    <div class="row">
                        <div class="col-md-8">
                            <div id="articles"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="boxed boxed--border bg-white">
                                <h3>Next to that a column with a button ‘add my project’ or ‘edit my profile’
                                    Besides of that I want a my stats, my coins and my <projects></projects></h3>
                            </div>
                        </div>
                    </div>

                    <!--
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
                                            Making the white-paper for the ICO launch in September.
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
                    -->
                </div>
            </div>
            <!--end of row-->
        </div>
        <!--end of container-->
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/community.css')}}">
@endsection

@section('scripts_footer')
    <script type="text/javascript" src="{{asset('js/community/article.js')}}"></script>

    <script type="text/javascript">
        $('.profile-like').click(function () {
          $that = $(this);
          var value = parseInt($that.attr('data-value'));
          $.post('{{route('profile.like', [$user->id])}}', {value: value})
            .done(function () {
              var text = value ? 'Unlike' : 'Like';
              $that.attr('data-value', value ? 0 : 1).text(text)
            });
        });

        $('.profile-follow').click(function () {
          $that = $(this);
          var value = parseInt($that.attr('data-value'));
          $.post('{{route('profile.follow', [$user->id])}}', {value: value})
            .done(function () {
              var text = value ? 'Unfollow' : 'Follow';
              $that.attr('data-value', value ? 0 : 1).text(text)
            });
        })
    </script>
@endsection
