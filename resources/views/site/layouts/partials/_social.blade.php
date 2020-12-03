<div id="social">
    <a href="{{ $misc->facebook }}" class="social-icon">
        <i class="fa fa-facebook" aria-hidden="true"></i>
    </a>

    <a href="{{ $misc->instagram }}" class="social-icon">
        <i class="fa fa-instagram" aria-hidden="true"></i>
    </a>

    <a href="mailto:{{ @$misc->emails_list[0]->value }}" class="social-icon">
        <i class="fa fa-envelope-o" aria-hidden="true"></i>
    </a>
</div>