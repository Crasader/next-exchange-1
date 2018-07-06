<form id="add-comment" class="panel {{ $suggestion->status=='pending'? 'd-none':'d-block'}}" action="/suggestions/{{ $suggestion->id }}/addComment" method="POST">
    {{ csrf_field() }}
    <h3>Add a comment</h3>
    <div class="form-group">
        <label class="control-label" for="formText">Message</label>
        <textarea class="form-control" rows="3" name="formText" id="formText"
                  placeholder="Your comment"
                  maxlength="500" minlength="10" required></textarea>
        <p class="help-block notChanging text-right counterContainer"><span id="counter"
                                                                            class="counter">0</span> / 500</p>
    </div>
    <div class="text-right">
        <input type="submit" class="button btn-primary" value="Post Comment"/>
    </div>
</form>