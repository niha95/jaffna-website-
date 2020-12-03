<div class="row">
    <div class="col-sm-8">
        <div class="section">
            <div class="form-group">
                {{ Form::label('Status', trans('labels.status'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    <label class="radio-inline">
                        {{ Form::radio('status', 'active', true) }} {{ trans('labels.statuses.active') }}
                    </label>
                    <label class="radio-inline">
                        {{ Form::radio('status', 'inactive', false) }} {{ trans('labels.statuses.inactive') }}
                    </label>
                </div>
            </div>

            <hr class="input_separator">

            <div class="form-group">
                {{ Form::label('FirstName', trans('labels.name'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-xs-6">
                            {{ Form::text('first_name', null,
                                ['class' => 'form-control', 'id' => 'FirstName',
                                    'placeholder' => trans('labels.first_name')]) }}
                        </div>
                        <div class="col-xs-6">
                            {{ Form::text('last_name', null,
                                ['class' => 'form-control', 'id' => 'LastName',
                                    'placeholder' => trans('labels.last_name')]) }}
                        </div>
                    </div>
                </div>
            </div>

            <hr class="input_separator">

            <div class="form-group">
                {{ Form::label('Email', trans('labels.email_address'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    {{ Form::email('email', null,
                        ['class' => 'form-control', 'id' => 'Email', 'placeholder' => trans('labels.email_address')]) }}
                </div>
            </div>

            <hr class="input_separator">

            <div class="form-group">
                {{ Form::label('Password', trans('labels.password'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-xs-6">
                            {{ Form::password('password',
                                ['class' => 'form-control', 'id' => 'Password',
                                    'placeholder' => trans('labels.password')]) }}
                        </div>
                        <div class="col-xs-6">
                            {{ Form::password('password_confirmation',
                                ['class' => 'form-control', 'id' => 'PasswordConfirmation',
                                    'placeholder' => trans('labels.password_confirmation')]) }}
                        </div>
                    </div>
                </div>
            </div>

            <hr class="input_separator">

            <div class="form-group">
                {{ Form::label('Status', trans('labels.role'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    <label class="radio-inline">
                        {{ Form::radio('role', 'admin', true) }} {{ trans('roles.admin') }}
                    </label>
                   
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                <button type="submit" class="btn btn-primary" name="save_and_continue">
                    {{ trans('actions.save_and_continue') }}</button>
            </div>
        </div>
    </div>

    @if(isset($activities))
        <div class="col-sm-4">
            <div class="section">
                <div id="User__ActivityLog">
                    <h2 class="section_title">{{ trans('labels.activity_log') }}</h2>

                    @foreach($activities as $activity)
                        <p class="text-muted">
                            {{ $activity->description_locale }}
                            @if($activity->hasReference())
                                <a href="{{ url($activity->reference) }}" target="_blank">
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                </a>
                            @endif
                        </p>
                    @endforeach

                    {!! $activities->links() !!}
                </div>
            </div>
        </div>
    @endif
</div>