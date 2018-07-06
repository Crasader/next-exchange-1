<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary btn-pink" id="setupAuthy2FA">
                {!! Html::image('/img/two-factor/authy.png', 'Authy', ['width'=> '20']) !!}
                Authy
            </button>

            <div class="col-xs-3"><br>
                <small>Powered by <a href="https://www.authy.com" target="_blank">Authy.com</a> - <a
                            href="https://play.google.com/store/apps/details?id=com.authy.authy">Android (APP)</a> - <a
                            href="https://itunes.apple.com/us/app/authy/id494168017">IOS (APP)</a></small>
            </div>

        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-primary btn-blue-grey" id="setupGoogle2FA">
                {!! Html::image('/img/two-factor/ga.png', 'Google Authenticator', ['width'=> '20']) !!}
                Google&nbsp;2FA
            </button>

            <div class="col-xs-3"><br>
                <small>Powered by <a href="https://www.google.com" target="_blank">Google, Inc.</a> - <a
                            href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Android
                        (APP)</a> - <a
                            href="https://itunes.apple.com/us/app/google-authenticator/id388497605">IOS (APP)</a>
                </small>
            </div>

        </div>
    </div>
</div>

<script>
    $('#setupAuthy2FA').on('click', function () {
        $('#setup2FAModal').find('.modal-loaded-content').load('/auth/two-factor/setup/1-authy');
    });

    $('#setupGoogle2FA').on('click', function () {
        $('#setup2FAModal').find('.modal-loaded-content').load('/auth/two-factor/setup/1-ga-secret-key');
    });
</script>