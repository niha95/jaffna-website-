@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($data, ['route' => 'control_panel.misc.edit_contact_data', 'class' => 'form-horizontal', 'method' => 'patch']) }}

        <div class="row">
            <div class="col-sm-8">
                @foreach(siteLocales() as $locale)
                    <div class="section">
                        <a class="section_collapse" role="button" data-toggle="collapse" href="#Section1_{{ $locale }}">&minus;</a>
                        <div id="Section1_{{ $locale }}" class="in">
                            <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                            <div class="form-group">
                                {{ Form::label('Address_' . $locale, trans('labels.address'), ['class' => 'control-label col-sm-2']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('address_' . $locale, null,
                                        ['class' => 'form-control', 'id' => 'Address_' . $locale, 'placeholder' => trans('labels.address'), 'rows' => 3]) }}
                                </div>
                            </div>

                            <hr class="input_separator">

                            <div class="form-group">
                                {{ Form::label('OtherContactData_' . $locale, trans('labels.other_contact_data'), ['class' => 'control-label col-sm-2']) }}
                                <div class="col-sm-10">
                                    {{ Form::textarea('other_contact_data_' . $locale, null,
                                        ['class' => 'form-control ck_editor', 'id' => 'OtherContactData_' . $locale, 'placeholder' => trans('labels.other_contact_data')]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="section">
                    <div class="form-group">
                        {{ Form::label('GoogleMap', trans('labels.google_map'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('google_map', null,
                                ['class' => 'form-control', 'id' => 'GoogleMap', 'placeholder' => trans('labels.google_map'), 'rows' => 3]) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="section">
                    <h2 class="section_title">
                        {{ trans('labels.phone_numbers') }} &nbsp;&nbsp;
                        <button type="button" class="btn btn-primary btn-xs" id="AddPhoneNumber">{{ trans('actions.add') }}</button>
                    </h2>
                    <div id="PhoneNumbersList">
                        @foreach($data->phone_numbers_list as $phone_number)
                            <div class="phone_number_widget">
                                {{ Form::text('phone_numbers[]', $phone_number, ['class' => 'form-control']) }}
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="removePhoneNumber(this)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="section">
                    <h2 class="section_title">
                        {{ trans('labels.emails') }} &nbsp;&nbsp;
                        <button type="button" class="btn btn-primary btn-xs" id="AddEmail">{{ trans('actions.add') }}</button>
                    </h2>
                    <div id="EmailsList">
                        @foreach($data->emails_list as $email)
                            <div class="email_widget">
                                {{ Form::text('emails[]', $email, ['class' => 'form-control']) }}
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="removeEmail(this)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="section">
                    <div class="form-group">
                        {{ Form::label('PostalCode', trans('labels.postal_code'), ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-9">
                            {{ Form::text('postal_code', null,
                                ['class' => 'form-control', 'id' => 'PostalCode', 'placeholder' => trans('labels.postal_code')]) }}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                    </div>
                </div>
            </div>
        </div>

    {{ Form::close() }}
@stop

@section('js')
    <script>
        $('#AddPhoneNumber').on('click', function(){
            $('#PhoneNumbersList').append(
                '<div class="phone_number_widget">' +
                    '<input type="text" name="phone_numbers[]" class="form-control" />' +
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="removePhoneNumber(this)">' +
                        '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                '</div>'
            );
        });

        $('#AddEmail').on('click', function(){
            $('#EmailsList').append(
                    '<div class="email_widget">' +
                        '<input type="text" name="emails[]" class="form-control" />' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="removeEmail(this)">' +
                            '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                    '</div>'
            );
        });

        function removePhoneNumber(element){
            var element = $(element);

            element.parents('.phone_number_widget').fadeOut('slow', function(){
                element.parents('.phone_number_widget').remove();
            });
        }

        function removeEmail(element){
            var element = $(element);

            element.parents('.email_widget').fadeOut('slow', function(){
                element.parents('.email_widget').remove();
            });
        }
    </script>
@append

@include('control_panel.common._ckeditor', ['images_manager' => 0])