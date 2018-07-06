@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')

    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <div id="project-create" class="row">

            </div>
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/community.css')}}">
@endsection

@section('scripts_footer')
    <script type="text/javascript" src="{{asset('/js/community/create_project.js')}}"></script>
@endsection
