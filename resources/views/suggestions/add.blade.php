@extends('_layouts.main')

@section('content')

    @include('_layouts.topnav')

    @include('_partials.status-panel')

    <link rel="stylesheet" href="/css/suggestions.css">


<main class="container">
    <h3 class="siteTitle mt-5">Suggest an improvement for NEXT.exchange</h3>
       
    @foreach ($errors->all() as $error)
        <li class="alert alert-danger">{{ $error }}</li>
    @endforeach
    <div class="col-xs-12 col-sm-12 col-md-8">
            <form class="panel newSuggestion" action="/suggestions" method="post" name="suggestionForm" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group ">
                    <label class="control-label" for="title">Title</label>
                    <input type="text" class="form-control next-input" name="title" id="title" value=""
                           placeholder="Your suggestion"
                           required pattern=".{5,100}" maxlength="100"
                           title="Title must be between 5 and 100 characters." autofocus/>
                    <p class="help-block notChanging text-right counterContainer">
                        <span id="titleCounter" class="counter invalid">0</span>/ 100
                    </p>
                </div>
            
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control " rows="5" name="description" id="description"
                              placeholder="Description of your suggestion (optional)"
                              maxlength="1000"></textarea>
                    <p class="help-block notChanging text-right counterContainer">
                        <span id="descriptionCounter" class="counter">0</span> / 1000
                    </p>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group">
                        <div>
                            <div><p class="alert alert-danger d-none">Please upload a smaller image</p></div>
                            <div id="divImagePreview" class="d-none">
                                <img id="imagePreview"/>
                                <p id="removeImage" class="ml-1">
                                    <span class="fa fa-times" aria-hidden="true" title="Remove"></span>
                                </p>
                            </div>
                            <button type="button" class="button mt-4 btn-xs btn-default" id="btnAddImage">Add image (optional)</button>
                            <span><p>(Max upload size: 250kb)</p></span>
                            <input type="file" name="file" id="file" onchange="readURL(this);"style="display:none">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="symbol">Symbol</label>
                        <input type="text" class="form-control next-input" name="symbol" id="symbol" value=""
                               placeholder="Choose a unique symbol"
                               required  maxlength="5"
                               title="Maximum size is 5" autofocus/>
                        <p class="help-block notChanging text-right counterContainer">
                            <span id="symbolCounter" class="counter invalid">0</span>/ 5
                        </p>
                    </div>
                </div>
            
                <div class="text-right">
                    <input type="submit" class="button btn-primary" id="post_suggestion" value="Post Suggestion"  />
                    <a class="button btn-default" style="padding: 15px 10px 15px 10px; "
                       href="javascript:history.back()" role="button">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@section('scripts_footer')

    <script src="{{ asset('/js/suggestions.js') }}"></script>

@endsection
