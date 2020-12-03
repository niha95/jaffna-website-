@if(Session::has('flash_message'))
    <div id="FlashMessage">
        <div class="alert alert-{{ \Session::get('flash_message_type') }}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p>{{ Session::get('flash_message') }}</p>
        </div>
    </div>
@endif
