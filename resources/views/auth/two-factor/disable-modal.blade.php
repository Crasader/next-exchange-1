<div class="modal fade" id="disable2FAModal" tabindex="-1" role="dialog" aria-labelledby="disable2FAModal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disable2FAModalLabel">Disable two-step verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                To Disable two-step verification, you’ll get an email with a Disable button. Click on the button to
                Disable two-step verification.
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{url('auth/two-factor/disable')}}">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        Ok
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>