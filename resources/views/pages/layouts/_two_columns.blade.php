<style>
    .side_bar {
        background-color: #0d2d62;
        color: #fff;
        padding: 5px 20px;
    }

    .module {
        padding: 20px 0;
        border-bottom: 1px solid #ccc;
    }

    .module:last-child {
        border-bottom: 0;
    }
</style>

<div class="row">
    <div class="col-sm-4">
        <div class="side_bar">
            @foreach($left as $m)
                <div class="module">
                    {!! $m !!}
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-sm-8">
        @foreach($right as $m)
            <div class="module">
                {!! $m !!}
            </div>
        @endforeach
    </div>
</div>


