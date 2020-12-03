<!DOCTYPE html>
<html lang="@yield('locale', app()->getLocale())">

@include('control_panel.layouts.partials._head')

<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
            @yield('content')
        </div>
    </div>
</div>

{{-- Scripts --}}
@include('control_panel.layouts.partials._scripts')

</body>
</html>