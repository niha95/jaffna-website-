<div class="modal fade" id="ConfirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('messages.confirm_operation') }}</h4>
            </div>
            <div class="modal-body">
                <div class="text-right">
                    <input type="hidden" name="requested_action" value="">
                    <button type="button" class="btn btn-danger"
                            onclick="confirmation_modal.hide()">{{ trans('actions.go_back') }}</button>
                    <button type="button" class="btn btn-primary"
                            onclick="confirmation_modal.confirmOperation()">{{ trans('actions.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        var confirmation_modal = {
            modal: $('#ConfirmationModal'),

            element: {},

            show: function(){
                this.modal.modal('show');
            },

            setRequestedAction: function(action){
                this.modal.find('input[name=requested_action]').val(action)
            },

            getRequestedAction: function(){
                return this.modal.find('input[name=requested_action]').val()
            },

            confirmOperation: function(){
                return this.fireOperationConfirmed();
            },

            fireOperationConfirmed: function(){
                var self = this;

                $(this).trigger('operation_confirmed', {
                    requested_action: self.getRequestedAction()
                });
            },

            hide: function(){
                this.modal.modal('hide');
            }
        };
    </script>
@append