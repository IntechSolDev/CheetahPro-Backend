<!DOCTYPE html>
<html class="no-js">
@include('web.includes.head')
<body>
@include('web.includes.header')
<main>
    @yield('content')
</main>
@include('web.includes.footer')
@yield('page-script')
</body>
</html>
