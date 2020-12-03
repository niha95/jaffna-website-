<!-- Site Word -->
<div class="about">
    <div class="col-md-7 col-sm-6 col-xs-12" style="padding:0">
        <div class="gmap-area wow fadeInUp animated" data-wow-delay="600ms" data-wow-duration="1000ms" style="visibility: visible; animation-duration: 1000ms; animation-delay: 600ms; animation-name: fadeInUp;">
            <div class="gmap" id="GoogleMap" data-options="{{ json_encode(['lat' => json_decode($misc->google_map)->lat, 'lng' => json_decode($misc->google_map)->lng, 'address' => $misc->address_locale, 'company' => $misc->site_name_locale, 'icon' => asset('assets/site/images/pin.png')]) }}" style="height: 400px;"></div>
        </div>
    </div>
    <div class="aboutUs col-md-5 col-sm-6 col-xs-12">
        <h2>{{ $misc->site_word_title_locale }}</h2>
        <p>{{ strip_tags($misc->site_word_content_locale) }}</p>
        <?php /*<br> <br>
        <div class="text-right">
            <a href="" style="color:#fff">MORE .....</a>
        </div>*/ ?>
    </div>
</div>