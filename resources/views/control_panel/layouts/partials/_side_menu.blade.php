<div id="SideMenu">
    <div id="SideMenuSearch">
        <input type="text" name="q" value="{{ $side_menu_search or '' }}" data-url="{{ route('control_panel.search') }}"
               id="SideMenuSearchBox" class="form-control" placeholder="{{ trans('actions.search') }}">
        <img src="{{ asset('assets/control-panel/images/loading.gif') }}" class="ajax_loader">
    </div>

    <div class="collapse navbar-collapse" id="SideMenuPanels">
        <div class="panel-group" id="Accordion" role="tablist" aria-multiselectable="true">
            @foreach($panels as $i => $panel)
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="Heading{{ $i }}">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse"
                               data-parent="#Accordion" href="#Collapse{{ $i }}"
                               aria-expanded="true" aria-controls="collapse{{ $i }}"
                               class="<?=$i != $current_panel ? 'collapsed' : ''?>">

                                @if(!empty($panel['icon']))
                                    <i class="{{ $panel['icon'] }}" aria-hidden="true"></i> &nbsp;&nbsp;
                                @endif

                                {{ $panel['title'] }}
                            </a>
                        </h4>
                    </div>
                    <div id="Collapse{{ $i }}" class="panel-collapse collapse <?= $i == $current_panel ? 'in' : '' ?>"
                         role="tabpanel" aria-labelledby="Heading{{ $i }}">
                        <div class="panel-body">
                            <ul>
                                @foreach($panel['options'] as $j => $option)
                                    <li class="<?= $j == $current_option ? 'current_option' : '' ?>">
                                        <a href="{{ $option['link'] }}">
                                            @if($option['icon'])
                                                <i class="{{ $option['icon'] }}" aria-hidden="true"></i> &nbsp;&nbsp;
                                            @endif

                                            {{ $option['label'] }}

                                            @if(isset($option['badge']))
                                                <span class="badge">{{ $option['badge'] }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@section('js')
    <script src="{{ asset('assets/control-panel/js/jquery.autocomplete.min.js') }}"></script>
    <script>
        $('#SideMenuSearchBox').autocomplete({
            serviceUrl: $('#SideMenuSearchBox').attr('data-url'),
            type: 'GET',
            dataType: 'json',
            triggerSelectOnValidInput: false,
            onSearchStart: function(){
                $(this).siblings('.ajax_loader').show();
            },
            onSearchComplete: function(){
                $(this).siblings('.ajax_loader').hide();
            },
            onSelect: function (suggestion) {
                window.location = suggestion.data;
            }
        });

    </script>
@append