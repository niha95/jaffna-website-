@extends('site.layouts.default')

@section('content')
   
   <section class="page-content">
            <div class="container">
                <div class="row">
                     
                     <div class="col-sm-7">
                        <div class="map">
                            {!! $misc->google_map_link !!}
                        </div>
                    </div>
                     <div class="col-sm-5">
                        
                        <p> <i class="fa fa-map-marker" aria-hidden="true"></i> 
                          {{ $misc->address_locale }}
                        </p>
                        @if($misc->phone_numbers_list)
                            @foreach($misc->phone_numbers_list as $phone)
                                <p><i class="fa fa-phone" aria-hidden="true"></i><span>{{ $phone->value }}</span></p>
                            @endforeach
                            @endif
                        @if($misc->emails_list)
                            @foreach($misc->emails_list as $email)
                                <p><i class="fa fa-envelope" aria-hidden="true"></i><span>{{ $email->value }}</span></p>
                            @endforeach
                        @endif
                    </div>
                  
                   
                    <div class="col-sm-12">
              
                        <h3>{{ trans('labels.touch') }}</h3>
                        
                        <form action="{{ route('site.send_contact_message') }}" id="main-contact-form" method="post" name="contact-form" class="contact-form">
                            @include('site.common._errors')
                            @include('site.common._flash_message')
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <input type="text" name="sender_name" class="form-control" id="SenderName" placeholder="{{ trans('labels.name') }} *" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="sender_email_address" class="form-control" id="SenderEmailAddress" placeholder="{{ trans('labels.email_address') }} *" required >
                            </div>
                            <div class="form-group">
                                <input type="text" name="sender_phone_number" class="form-control" id="SenderPhoneNumber" placeholder="{{ trans('labels.phone_number') }}">
                            </div>
                            <div class="form-group">
                                <textarea rows="10" class="form-control" placeholder="{{ trans('labels.message') }}" name="message" required></textarea>
                                </div>
                                <div class="form-group">
                                <input type="hidden" name="subject" class="form-control" id="Subject" value="{{trans('labels.contact_us')}}">
                            <button class="btn" type="submit">{{trans('labels.send') }}</button>
                            <button class="btn" type="reset">{{trans('labels.rest') }}</button>
                            </div>
                        </form>
                    </div>
            </div>
        </section>
        

   
@append

<?php /*
@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnGAU6SVZCI3bq0csAXS60jTAQU1XZdl4"></script>
    <script src="{{ asset('assets/site/js/jquery.gmap/jquery.gmap.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/map.js') }}"></script>
@append
 */ ?>