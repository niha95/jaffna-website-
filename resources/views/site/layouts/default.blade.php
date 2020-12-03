<!DOCTYPE html>
<html>
 
@include('site.layouts.partials._head')

  <body class="container-fluid page-body">
   
    @include('site.layouts.partials._navbar')
     
    @yield('content')

    @include('site.layouts.partials._footer')

    @include('site.layouts.partials._scripts')
</body>
</html>