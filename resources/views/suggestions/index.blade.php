@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')

    <link rel="stylesheet" href="/css/suggestions.css">
    
    <section class="section-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-7"><p class="section-header__title">Suggestions</p>
                    <p class="section-header__desc">Suggest your favorite coin/token here, when 1000+ votes are reached,
                        there is a big chance that it will be listed on NEXT for free<sup>*</sup>
                    </p>
                    <p class="small text-white">* In a hurry? Try our priority or premium listing.</p></div>
                <div class="col-12 col-sm-5 text-right"><a class="section-header__btn" href="/suggestions/add">Add
                        token/coin
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

    <section style="background-color: #00AEFF">
        <div class="container ptb20">
            <div class="next-table">
                <div class="suggestionsTable__title">
                    <div class="">Suggestion list</div>
                    <div class="">
                        <button class="btn btn--small btn--outline active" id="btn-popular"><i class="fa fa-star"
                                                                                        aria-hidden="true"></i> Top
                        </button>
                        <button class="btn btn--small btn--outline" id="btn-new"><i class="fa fa-clock-o"></i> New
                        </button>
                        <span class="dropdown" role="group" aria-label="filterBy">
                        <button class="btn btn--small btn--outline dropdown-toggle" href="javascript:void(0);"
                                role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false">
                            <span id="selected">All({{$suggestions->count()}})</span>
                        </button>
                        <ul class="dropdown-menu suggestionsTable__title__dropdown" aria-labelledby="dropdownMenuLink">
                            <li class="dropdown-item"><a href="javascript:void(0);" id="item-all">All</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-item"><a href="javascript:void(0);" id="item-all-except-done">All except done</a></li>
                            <li class="dropdown-item"><a href="javascript:void(0);" id="item-underconsideration">Under consideration</a></li>
                            <li class="dropdown-item"><a href="javascript:void(0);" id="item-planned">Planned</a></li>
                            <li class="dropdown-item"><a href="javascript:void(0);" id="item-not-planned">Not planned</a></li>
                            <li class="dropdown-item"><a href="javascript:void(0);" id="item-done">Done</a></li>
                        </ul>
                    </span>
                </div>
            </div>

                <div class="clear"></div>
                <div class="next-table-content__left-search form-inline" id="searchForm"><input class='search'
                                                                                                type="search"
                                                                                                aria-label="Search"
                                                                                                placeholder="Search for currency"
                                                                                                value="">
                    <button type="button" id="search-suggestion">
                        <svg width="16" height="16" viewBox="0 0 16 16">
                            <path stroke="#828EA1" stroke-linejoin="round" stroke-width="2"
                                  d="M14 14l-2.9-2.9m-3.767 1.567A5.333 5.333 0 1 0 7.333 2a5.333 5.333 0 0 0 0 10.667z"></path>
                        </svg>
                </button>
                </div>
                <div class="next-table-content__left-labels">
                    <div class="col-6 coins__sort">Suggested Currency/Token</div>
                    <div class="col-2 text-right coins__sort">Votes</div>
                    <div class="col-2 text-right coins__sort">Comments</div>
                    <div class="col-2 text-right coins__sort"></div>
                </div>
                <div class="next-table-content__left-funds-scroll">
                    <div id="top-sorted">
                        <div>
                            <div class="next-table-content__left-currencies" id="suggestionsList">
                                @foreach ($suggestions as $suggestion)
                                    @include('suggestions.item')
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="center">{{ $suggestions->links() }}</div>
        </div>


    </section>



@endsection

@section('scripts_footer')

    <script src="{{ asset('/js/suggestions.js') }}"></script>

@endsection
 