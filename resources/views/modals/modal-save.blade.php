<div class="modal fade modal-success modal-save" id="confirmSave" role="dialog" aria-labelledby="confirmSaveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="mr-1">&times;</span></button>
                <h4 class="modal-title">{{ Lang::get('modals.confirm_modal_title_text') }} </h4>
            </div>
            <div class="modal-body">
                <p>{{ Lang::get('modals.confirm_modal_title_text') }} </p>
            </div>
            <div class="modal-footer">
                {!! Form::button('<i class="fa fa-fw fa-times" aria-hidden="true"></i> ' .'Cancel', array('class' => 'btn
                btn-outline pull-left btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )) !!} {!! Form::button('
                <i
                    class="fa fa-fw fa-save" aria-hidden="true"></i> ' . 'Save', array('class' => 'btn btn-success pull-right btn-flat', 'type' => 'button', 'id' => 'confirm'
                    )) !!}
            </div>
        </div>
    </div>
</div>