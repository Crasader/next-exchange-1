<div class="panel suggestionComments">
    <h2>
        <i class="fa fa-comment"></i>
        Comments: {{$suggestion->comments_count >= 1000 ? number_format($suggestion->comments_count/1000,1) . 'k': $suggestion->comments_count}}
    </h2>
    <ul class="commentList">
        @foreach ($suggestion->comments->reverse() as $comment)
            <li id="comment8872" class="commentItem ">
                <span class="theDate">{{ Carbon\Carbon::parse($comment->created_at)->format('d M, Y') }}</span><br/>
                <span class="theAuthor"><strong>{{$comment->name}}</strong></span>
                <p>{{$comment->body}}</p>
            </li>
        @endforeach
    </ul>
</div>
