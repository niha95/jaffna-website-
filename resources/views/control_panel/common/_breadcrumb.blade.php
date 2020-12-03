<ol class="breadcrumb">
    <?php $i = 0 ?>
    @foreach($breadcrumb as $item)
        @if(++$i == count($breadcrumb))
            <li class="active">
                <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                {{ $item['label'] }}
            </li>
        @else
            <li>
                <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                <a href="{{ !empty($item['link']) ? $item['link'] : '#' }}">{{ $item['label'] }}</a>
            </li>
        @endif
    @endforeach

    @if(count($breadcrumbButtons) != 0)
        <div id="BreadcrumbButtons">
            @foreach($breadcrumbButtons as $btn)
                <a href="{{ $btn['link'] }}" class="btn btn-{{ $btn['type'] }} btn-xs">
                    @if(!empty($btn['icon'])) <i class="{{ $btn['icon'] }}" aria-hidden="true"></i> @endif
                    @if(!empty($btn['label'])) <span>{{ $btn['label'] }}</span> @endif
                </a>
            @endforeach
        </div>
    @endif
</ol>