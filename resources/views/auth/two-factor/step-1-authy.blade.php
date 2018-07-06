<div class="modal-body">
    <div class="col-12">
        <form id="setupAuthyForm" method="POST" action="{{url('auth/two-factor/enable/authy')}}">
            {{csrf_field()}}

            <div class="row">
                <div class="form-group">
                    <label for="exampleSelect1">Country:</label>
                    <select id="authy-countries" name="country-code"></select>
                </div>

                <div class="form-group">
                    <label for="exampleSelect1">Phone number:</label>
                    <input id="authy-cellphone" type="tel" name="authy-cellphone" required/>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="send_sms"/>
                        Send two-factor token via SMS
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" id="setupAuthyBack" class="btn btn-secondary">Back</button>
    <button type="button" id="setupAuthySubmit" class="btn btn-primary">Submit</button>
</div>
<script>
    $('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/flags.authy.css"/>').appendTo('head');
    $('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/form.authy.css"/>').appendTo('head');
    $.getScript('https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/form.authy.js', function () {
        Authy.UI.instance();
    });

    $('#setupAuthyBack').on('click', function () {
        $('#setup2FAModal').find('.modal-loaded-content').load('/auth/two-factor/setup/0-select-provider');
    });

    $('#setupAuthySubmit').on('click', function () {
        $('#setupAuthyForm').submit();
    });
</script>