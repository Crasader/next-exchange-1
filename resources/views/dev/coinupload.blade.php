@if (count($errors) > 0)
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif


<form action="/upload/image/coin" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    Coin name:
    <br />
    <input type="text" name="name" />
    <br /><br />
    Token image (can attach more than one):
    <br />
    <input type="file" name="photo" />
    <br /><br />
    <input type="submit" value="Upload" />
</form>
