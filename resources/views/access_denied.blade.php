<!DOCTYPE html>
<html>
<head>
    <title>Next Exchange | Access Denied</title>
</head>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <h3 class="text-center">BANNED</h3>
            </div>
            <div class="text-center">
                <p>
                <h4>
                    Your IP has been banned, and you are no longer able to access this website.
                </h4>
                <h4 style="color: blue;">{!! \Request::ip() !!}</h4>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
