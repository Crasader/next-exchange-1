@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')
    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <div class="row" id="projects"></div>
        </div>
    </section>
@endsection

@section('scripts_footer')
    <script type="text/javascript" src="{{asset('js/community/project.js')}}"></script>
@endsection