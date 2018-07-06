@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')
    <section class="section-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-7"><p class="section-header__title">Community</p>
                    <p class="section-header__desc">Our exchange is powered by the community. Without them there is no
                        exchange possible.</p></div>
                <div class="col-12 col-sm-5 text-right"><a class="section-header__btn" href="/register">Join our
                        movement
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g fill="none" fill-rule="evenodd">
                                <path fill="none" d="M0 0h16v16H0z"></path>
                                <path stroke="#FFF" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.33"
                                      d="M8 4l4 4-4 4 4-4H4h4"></path>
                            </g>
                        </svg>
                    </a></div>
            </div>
        </div>
    </section>
    <section class="ptb40">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="heading-block">
                        <h3>Current Members</h3> <br>
                    </div>
                </div>
                @foreach($users as $user)
                    <div class="col-sm-4">
                        <div class="feature feature-1 boxed boxed--border">
                            <span class="badge badge-default float-right">{{ $user->title}}</span>
                            <img class="card__avatar" src="{{ Gravatar::get($user->email) }}"
                                 alt="{{ substr($user->name,0, -3).'***' }}">
                           <div class="user-details">
                            <p class="f16 text-black"><?= substr($user->name,0, -3).'***'; ?></p>
                            <p>
                                {{ $user->about_me}}
                            </p>
                            <!-- <a href="/profile"> -->
                            <a href="{{ url('profile', $user->id) }}">
                                View Profile
                            </a>
                            </div>
                        </div>
                        <!--end feature-->
                    </div>
                @endforeach
            </div>
            <nav aria-label="Page navigation example">
            {{ $users->links() }}
            </nav>
        </div>
        <!--end of row-->
        </div>
        <!--end of container-->
    </section>


@endsection

