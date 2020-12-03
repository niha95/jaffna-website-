<div class="col-sm-12"><h2> {!! $m_title !!}</h2>

 @if($current_locale == 'ar')
<p style="text-align:right">{!! $m_content !!}</p>
@else
<p>{!! $m_content !!}</p>
@endif
</div>