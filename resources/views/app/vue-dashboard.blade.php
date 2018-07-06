@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav_exchange')
    @include('_partials.flashalert')

    <div id="app"></div>

    <!-- Modal -->
    <div class="modal fade" id="user-acceptance" tabindex="-1" role="dialog" style="display: none"
         aria-labelledby="Modal-Title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalTitle">Disclaimer</h5>
                    <button type="button" class="close not-accepting" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Caution: NEXT.exchange is currently in its beta stage.</h4>
                    While trade functionality and wallets work, we urge that you please do not place a significant
                    portion of your assets on the platform at the moment due to ongoing testing.
                    By accepting this disclaimer, you agree that NEXT is not liable for any loss of assets.

                    <br><br>Thank you for your understanding.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary not-accepting">Reject</button>
                    <button type="button" id="save_details" class="btn btn-primary">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let flag = "{!! @$user_disclaimer !!}";

            if (flag == 0) {
                $('#user-acceptance').modal({backdrop: 'static', keyboard: false})
                    .addClass('show')
            }
        });
    </script>


@endsection

@section('scripts_footer')


    <script src="{{ asset('/js/app.js') }}"></script>

    <script>
        var coinmarketcaps = {};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#save_details').on('click', function () {

            $.ajax({
                "type": "POST",
                "url": '/saveuserdisclaimer',
                "dataType": "json",
                "success": function (response) {
                    if (response == 1) {
                        $('#user-acceptance')
                            .removeClass('show')
                            .modal("hide");
                    }
                }
            });
        });

        $('.not-accepting').on('click', function () {

            $('#user-acceptance')
                .removeClass('show');
            window.location.href = '/';
        });


        // On ICO page
        $('[data-card]').on('click', function () {
            $(this).toggleClass('card--open');
        });

    </script>
@endsection