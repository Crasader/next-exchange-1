@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')

@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/community.css')}}">
@endsection

@section('scripts_footer')
    <script type="text/javascript" src="{{asset('/js/community/app.js')}}"></script>
@endsection
