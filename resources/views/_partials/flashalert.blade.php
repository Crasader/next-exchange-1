@section('styles-flashalert')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
@endsection

@section('scripts-flashalert')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    @if(!empty(Session::get('flashalert')))
        <script>
            swal({
                title: "{{session('flashalert.title')}}",
                text: "{{session('flashalert.message')}}",
                type: "{{session('flashalert.level')}}",
                timer: "{{config('flashalert.hide_timer')}}",
                showConfirmButton: "{{config('flashalert.show_confirmation_button')}}"
            });
        </script>
    @endif

@endsection
