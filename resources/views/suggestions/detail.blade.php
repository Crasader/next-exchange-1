<?php
    // Set status-class 
    switch ($suggestion->status) {
        case 'under-consideration': $labelClass = 'sLabelUC';break;
        case 'not-planned': $labelClass = 'sLabelNP';break;
        case 'planned': $labelClass = 'sLabelP';break;
        case 'pending': $labelClass = 'sLabelPn';break;
        case 'done': $labelClass = 'sLabelR';break;
        default: $labelClass = 'sLabelTag';break;
    }
    if($suggestion->status=='pending')
        $isPending = true;
    else 
        $isPending = false;
?>
@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')

    <link rel="stylesheet" href="/css/suggestions.css">
<main class="container mt-5">
    <p>
        <a href="/suggestions" class="backToAll">
            <i class="fa fa-arrow-circle-o-left"></i> All suggestions
        </a>
    </p>
    <div class="clearfix">
        <h1 class="siteTitle">{{$suggestion->title}}</h1>
    </div>
    <div class="panel suggestionBody clearfix">
        <div class="sNumbers pull-left">
            <div class="sVotes pull-left text-center">
                <span class="sNumber">
                    <span id="upvoteCount" title="{{$suggestion->votes_count}}">
                        {{$suggestion->votes_count >= 1000 ? number_format($suggestion->votes_count/1000,1) . 'k': $suggestion->votes_count }}
                    </span>
                     <span class="sLabel hidden-xs">votes</span>
                </span>
            </div>
            <a href="#cForm" class="sComments pull-left text-center">
                <span class="sNumber">{{$suggestion->comments_count >= 1000 ? number_format($suggestion->comments_count/1000,1) . 'k': $suggestion->comments_count}}
                    <span class="sLabel hidden-xs">comments</span>
                </span>
            </a>
            @if ($suggestion->votes_count === 0)
                <button id="suggestion-{{$suggestion->id}}" class="button btnVoteUp btn-success upvote"
                        @if ($isPending) disabled @endif>
                    Upvote
                </button>
            @else
                <button id="suggestion-{{$suggestion->id}}" class="button btnVoteUp btn-votedup downvote">
                    Voted Up
                </button>
            @endif
        </div>
        <div class="suggestionDescription">
            <p>{{$suggestion->description}}</p>
            <p><strong> Symbol: </strong><span class="text-uppercase">{{$suggestion->symbol}}</span></p>
        </div>
        @if ($suggestion->image)
            <div class="suggestionScreenshot">
                <img id="suggestion-thumbnail"
                     src="{{ \App\Http\Controllers\EncryptionController::secureImageDownload($suggestion->image, 'suggestions') }}">
            </div>
        @endif
        <hr class="clear"/>
        <div class="suggestionMeta mt-3">Suggested by: <strong>{{$suggestion->name}}</strong> 
        <time datetime="{{$suggestion->created_at}}">({{ Carbon\Carbon::parse($suggestion->created_at)->format('d M') }})</time>
        @if ($isPending)
            <div class="approve">
                <button id="suggestion-{{$suggestion->id}}-{{$suggestion->short_name}}" context="single" class="btn-primary btn-approve">Approve</button>
                <button id="suggestion-{{$suggestion->id}}" context="single" class="btn-outline-primary btn-reject">Reject</button>
            </div>
        @endif
        <br>
        <p class="{{ $isPending ? 'd-none':'d-inline-block'}}" id="status-panel">
            <span class="label <?= $labelClass ?> mt-3" data-toggle="tooltip" data-placement="bottom"
             title="This suggestion is open for upvotes and comments.">{{$suggestion->status}}
            </span>
            @if (Auth::check() && Auth::user()->hasRole('admin'))
                <select class="custom-select col-sm-auto" id="set-status" title="{{$suggestion->id}}">
                    <option selected disabled>Change Status</option>
                    <option value="under-consideration">Under Consideration</option>
                    <option value="not-planned">Not Planned</option>
                    <option value="planned">Planned</option>
                    <option value="done">Done</option>
                </select>
            @endif
        </p>
    </div>
</div>
@include('suggestions.comment')
@include('suggestions.addComment')
</main>
@endsection

@section('scripts_footer')

    <script src="{{ asset('/js/suggestions.js') }}"></script>    
@endsection