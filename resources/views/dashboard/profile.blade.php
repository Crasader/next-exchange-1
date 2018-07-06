@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')

    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="boxed boxed--lg boxed--border bg-white">
                        <div class="text-block text-center">
                            <img class="image--md" src="{{ Gravatar::get($user->email) }}" alt="{{ $user->name }}">
                            <br>
                            <span class="h5"><strong>{{ $user->name }}</strong></span>
                            <br>
                            <span>{{ $user->about_me }}</span>
                            <span class="label">{{ $user->title }}</span>
                        </div>
                        <style>
                            .btn-profile {
                                color: #155724 !important;
                                background: transparent !important;
                                border: 1px solid #edeede;
                                padding: 0;
                                width: 110px;
                            }

                            .btn-profile:hover {
                                background-color: #a3a3a3 !important;
                            }

                        </style>
                        <div class="btn-group">
                            <a href="{{route('project.create')}}" class="btn btn-primary btn-profile disabled">Add
                                Project</a>
                            <a href="{{route('profile_update')}}" class="btn btn-primary btn-profile btn-flat">Edit
                                profile</a>
                        </div>
                        <div class="text-block">
                            <div class="profile-stats-block" style="border-bottom: 1px solid #ececec">
                                <div class="profile-stat">
                                    <p><strong>{{ $user->connections_count }}</strong></p>
                                    <p>Connections</p>
                                </div>
                                <div class="profile-stat">
                                    <p><strong>{{ $user->followers_count }}</strong></p>
                                    <p>Followers</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="boxed boxed--lg boxed--border bg-white">
                        <h4><strong>My stats</strong></h4>
                        <div class="profile-stats-block" style="border-bottom: 1px solid #ececec">
                            <div class="profile-stat">
                                <p><strong>{{ $user->likes_count }}</strong></p>
                                <p>Likes</p>
                            </div>
                            <div class="profile-stat">
                                <p><strong>{{ $user->articles_count }}</strong></p>
                                <p>Articles</p>
                            </div>
                            <div class="profile-stat">
                                <p><strong>{{ $user->projects_count }}</strong></p>
                                <p><a href="{{route('projects.list')}}">Projects</a></p>
                            </div>
                            <div class="profile-stat">
                                <p><strong>{{ $user->followers_count }}</strong></p>
                                <p>Views</p>
                            </div>
                        </div>
                    </div>

                </div>
                <!--
                <div class="col-md-8">
                    <div id="articles"></div>
                </div>
                -->
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/community.css')}}">
@endsection

@section('scripts_footer')
    <script type="text/javascript" src="{{asset('/js/community/article.js')}}"></script>
@endsection
