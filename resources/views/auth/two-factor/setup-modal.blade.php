<div class="modal fade" id="setup2FAModal" tabindex="-1" role="dialog" aria-labelledby="setup2FAModal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setup2FAModalLabel">Two-factor Authentication</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-loaded-content">
                <div class="modal-body">
                    Loading...
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            $('#setup2FAModal').on('shown.bs.modal', function () {
                $(this).find('.modal-loaded-content').html('<div class="modal-body">Loading...</div>').load('/auth/two-factor/setup/0-select-provider');
            });
        });
    </script>
@endpush