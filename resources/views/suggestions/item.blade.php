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

<li id="suggestion{{$suggestion->id}}" class="suggestionListItem clearfix">
    <div class="col-1">
        @if (!$isPending)
            <img class="icon"
                 src="{{ \App\Http\Controllers\EncryptionController::secureImageDownload($suggestion->image, 'suggestions') }}"
                 onerror="this.src='/img/next-exchange-logo-28x28.png'" alt="{{$suggestion->short_name}}">
        @else
            <img class="icon" src="/img/next-exchange-logo-28x28.png">
        @endif
    </div>
    <div class="col-5">
  
        @if (!$isPending || ($current_user ? $current_user->hasRole('admin') : false))
            <a href="/suggestions/{{$suggestion->short_name}}">{{$suggestion->title}}</a>
        @else
            <a style="color:#8B8C8D">{{$suggestion->title}}</a>
        @endif
        <span style="margin: 0; border: 0; margin-left: 10px; padding: 2px 4px 2px 4px; color: #fff; font-size: 80%; border-radius: 3px; "
           class=" <?= $labelClass ?>" data-toggle="tooltip" data-placement="bottom"
            title="This suggestion is open for upvotes and comments.">{{$suggestion->status}}
        </span><br>Suggested by:
        <strong>{{$suggestion->name}}</strong> ({{ Carbon\Carbon::parse($suggestion->created_at)->format('d M') }})
        @if ( ($current_user != '' ? $current_user->role_id == 1 : false) && $isPending )
            <div class="approve">
                <button id="suggestion-{{$suggestion->id}}-{{$suggestion->short_name}}" context="list" class="btn-primary btn-approve">Approve</button>
                <button id="suggestion-{{$suggestion->id}}" context="list" class="btn-outline-primary btn-reject">Reject</button>
            </div>
        @endif
    </div>
    <div class="col-2 text-right ">{{$suggestion->votes_count >= 1000 ? number_format($suggestion->votes_count/1000,1) . 'k': $suggestion->votes_count }}</div>
    <div class="col-2 text-right ">{{$suggestion->comments_count >= 1000 ? number_format($suggestion->comments_count/1000,1) . 'k': $suggestion->comments_count}}</div>
    <div class="col-2 text-right ">
        <a href="/suggestions/{{$suggestion->short_name}}" class="btn btn--ssmall btn--outline <?= $isPending ? 'disabled' : '' ?>" id="btn-popular">
            <i class="fa fa-arrow-up" aria-hidden="true"></i>Upvote
        </a>
    </div>
</li>

