<div class="modal-body">
    Configure your authentication in 2 simple steps:

    <ul>
        <li>1. Add a new time-based token.</li>
        <li>2. Use your app to scan the barcode below or enter your secret key <span>manually</span></li>
    </ul>
    <img src="{{$qrUrl}}" alt="Secret key QR-code">
    <div>{!! $gsecret !!}</div>
</div>
<div class="modal-footer">
    <button type="button" id="setupGoogle2FABack" class="btn btn-secondary">Back</button>
    <button type="button" id="setupGoogle2FAConfirm" class="btn btn-primary">Next</button>
</div>

<script>
    $('#setupGoogle2FABack').on('click', function () {
        $('#setup2FAModal').find('.modal-loaded-content').load('/auth/two-factor/setup/0-select-provider');
    });

    $('#setupGoogle2FAConfirm').on('click', function () {
        $('#setup2FAModal').find('.modal-loaded-content').load('/auth/two-factor/setup/2-ga-totp-confirm');
    });
</script>