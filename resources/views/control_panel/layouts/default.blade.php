<!DOCTYPE html>
<html lang="@yield('locale', app()->getLocale())">

@include('control_panel.layouts.partials._head')

<body>
{{-- Top Navbar --}}
@include('control_panel.layouts.partials._navbar')

<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-2 col-sm-3">
            @include('control_panel.layouts.partials._side_menu')
        </div>

        {{-- Main Content --}}
        <div class="col-md-10 col-sm-9">

            <!-- Session Message -->
            <div id="bottom_margin_10">
                @include('control_panel.common._flash_message')
            </div><!-- /Session Message -->

            <!-- Breadcrumb -->
            <div id="bottom_margin_10">
                @include('control_panel.common._breadcrumb')
            </div><!-- /Breadcrumb -->

            <!-- Errors -->
           <!-- <div id="bottom_margin_10">
                @include('control_panel.common._errors')
            </div><!-- /Errors -->

            <!-- Sections -->
            <div id="-Sections bottom_margin_10">
                @yield('content')
            </div><!-- /Sections -->

          <!--  @if(config('app.debug'))
                <hr>

                <div>
                    <?php var_dump(DB::getQueryLog()) ?>
                </div>
            @endif

        </div><!-- /Second Column -->
    </div>
</div>

@include('control_panel.common._modal')
@include('control_panel.common._confirmation_modal')

{{-- Footer --}}
@include('control_panel.layouts.partials._footer')

{{-- Scripts --}}
@include('control_panel.layouts.partials._scripts')

</body>
</html>