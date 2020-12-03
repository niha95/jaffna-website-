@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($data, ['route' => 'control_panel.misc.edit_contact_data', 'class' => 'form-horizontal', 'method' => 'patch']) }}

        <div class="row">
            <div class="col-sm-7">
                @foreach(siteLocales() as $locale)
                    <div class="section">
                        <a class="section_collapse" role="button" data-toggle="collapse" href="#Section1_{{ $locale }}">&minus;</a>
                        <div id="Section1_{{ $locale }}" class="in">
                            @if(count(siteLocales()) > 1)
                                <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                            @endif
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
                    <h2 class="section_title">{{ trans('labels.google_map') }}</h2>
                    {{ Form::hidden('google_map', null, ['id' => 'GoogleMapField']) }}

                    <p class="help-block">{{ trans('messages.choose_place_on_google_map') }}</p>

                    <div id="GoogleMap"></div>
                    
                    <br />
                    <div class="form-group">
                        {{ Form::label('Google Map Link', trans('labels.google_map_link'), ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-9">
                            {{ Form::text('google_map_link', null,
                                ['class' => 'form-control', 'id' => 'google_map_link', 'placeholder' => trans('labels.google_map_link')]) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-5">
                <div id="ContactDataApp"
                     :phone_numbers="{{ $data->phone_numbers }}"
                     :emails="{{ $data->emails }}"
                     :translations="{{ $translations }}"
                >
                    

                    <div class="section">
                        <entities-list :entities.sync="emails" :entities_label="translations.emails"></entities-list>
                        <input type="hidden" name="emails" v-model="emails_json">
                    </div>
                </div>

                <template id="EntitiesTemplate">
                    <div id="Entities">
                        <h2 class="section_title">
                            @{{ entities_label }} &nbsp;&nbsp;
                            <button type="button" class="btn btn-primary btn-xs" v-on:click="addEntity">{{ trans('actions.add') }}</button>
                        </h2>
                        <div id="EntitiesList">
                            <div class="entity_widget" v-for="entity in entities">
                                <div class="row">
                                    <div class="col-xs-8">
                                        @foreach(siteLocales() as $locale)
                                            <div class="form-group no_margin">
                                                <label for="For_{{ $locale }}" class="col-xs-5">{{ localizedLabel('for', $locale) }}</label>
                                                <div class="col-xs-7">
                                                    <input type="text" v-model="entity.label_{{ $locale }}"
                                                           class="form-control" placeholder="{{ LocalizedLabel('for', $locale) }}"
                                                    >
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-xs-4 text-right">
                                        <div class="action_buttons">
                                            <a href="javascript:void(0)" v-on:click.prevent="deleteEntity(entity)">
                                                <i class="fa fa-trash-o fa-fw fa-lg" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" v-on:click="featureEntity(entity)">
                                                <i class="fa fa-star fa-fw fa-lg @{{ entity.featured ? 'selected' : '' }}" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <input type="text" v-model="entity.value" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

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

@include('control_panel.common._ckeditor', ['images_manager' => 0])

@section('js')
    <script src="{{ asset('assets/control-panel/js/vue.js') }}"></script>
    <script>
        var vm = new Vue({
            el: '#ContactDataApp',

            props: ['phone_numbers', 'emails', 'translations'],

            computed: {
                'phone_numbers_json': function(){
                    return JSON.stringify(this.phone_numbers);
                },
                'emails_json': function(){
                    return JSON.stringify(this.emails);
                }
            },

            components: {
                'entities-list': {
                    template: '#EntitiesTemplate',

                    props: ['entities', 'entities_label'],

                    methods: {
                        addEntity: function (){
                            var entity = {};

                            $.each(locales, function(i, locale){
                                entity['label_' + locale] = '';
                            });

                            entity.value = '';
                            entity.featured = false;

                            this.entities.push(entity);
                        },

                        deleteEntity: function(phone){
                            this.entities.$remove(phone)
                        },

                        featureEntity: function(entity){
                            entity.featured = !entity.featured;
                        }
                    }
                }
            }
        });
    </script>

    <script>
        var map;
        var markers = [];
        var currentLatLng = {
            lat: 30.015152,
            lng: 31.213248
        };
        var setMarker = false;

        function initMap() {
            var savedPosition = JSON.parse($('#GoogleMapField').val());

            if(savedPosition.lat.length != 0 && savedPosition.lat.length != 0) {
                currentLatLng = savedPosition;
                setMarker = true;
            }

            map = new google.maps.Map(document.getElementById('GoogleMap'), {
                center: currentLatLng,
                zoom: 12
            });

            if(setMarker) {
                placeMarkerAndPanTo(currentLatLng, map);
            }

            map.addListener('click', function(e) {
                var latLng = e.latLng;

                currentLatLng = latLng;

                removeMarkers();

                placeMarkerAndPanTo(latLng, map);

                setMapField();
            });

            function placeMarkerAndPanTo(latLng, map){
                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map
                });

                markers.push(marker);

                map.panTo(latLng);
            }

            function removeMarkers(){
                for(i = 0; i < markers.length; i++){
                    markers[i].setMap(null);
                }
            }

            function setMapField() {
                $('#GoogleMapField').val(JSON.stringify(currentLatLng));
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnGAU6SVZCI3bq0csAXS60jTAQU1XZdl4&callback=initMap"
            async defer></script>
@append