<div id="Navbar">
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#NavbarLinks" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ changeCPLocaleUrl(otherCPLocale()) }}" class="btn btn-default navbar-btn navbar-toggle collapsed"
                   id="CPLocaleSwitcher">{{ trans('locales.' . otherCPLocale() . '_semi') }}</a>
                <button type="button" class="navbar-toggle collapsed" id="SideMenuToggle"
                        data-toggle="collapse" data-target="#SideMenuPanels"
                        aria-expanded="false" title="{{ trans('actions.toggle_side_menu') }}">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </button>
                <a class="navbar-brand" href="{{ route('control_panel.home', [app()->getLocale()]) }}">
                    <i class="fa fa-cogs" aria-hidden="true"></i> &nbsp;&nbsp;
                    {{ trans('labels.control_panel') }}
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="NavbarLinks">
                <p class="navbar-text">
                    {{ trans('messages.logged_in_as') }}
                    <span id="LoggedInUser">{{ auth()->user()->email  }}</span>
                </p>
                <ul class="nav navbar-nav navbar-right">
                    @foreach (config('control_panel.locales') as $locale)
                        @if($locale == app()->getLocale()) @continue @endif
                        <a href="{{ changeCPLocaleUrl($locale) }}" class="btn btn-default navbar-btn" id="CPLocaleSwitcher"><span>{{ trans('locales.' . $locale . '_semi') }}</span></a>
                    @endforeach
                    
                    <li>
                        <form action="{{ route('control_panel.do_logout') }}" id="LogoutForm"></form>
                        <a href="{{ route('control_panel.do_logout') }}" id="LogoutLink" class="navbar-link">
                            <i class="fa fa-sign-out" aria-hidden="true"></i> &nbsp;&nbsp;
                            {{ trans('actions.logout') }}
                        </a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav><!-- End of Navbar -->
</div>