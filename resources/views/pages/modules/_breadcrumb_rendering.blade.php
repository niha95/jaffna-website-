<style>
    .breadcrumb {
        margin-top: 20px;;
    }
</style>

<div class="container">
    <ul class="breadcrumb">
        @foreach($breadcrumb as $i)
            @if(!empty($i['link']))
                <li><a href="{{ $i['link'] }}">{{ $i['label'] }}</a></li>
            @else
                <li class="active">{{ $i['label'] }}</li>
            @endif
        @endforeach
    </ul>
</div>