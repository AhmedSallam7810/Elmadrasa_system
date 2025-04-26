<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Tiny Dashboard - A Bootstrap Dashboard Template</title>
    @include('partials.css')
  </head>
  <body class="vertical  light rtl ">
    <div class="wrapper">
      @include('partials.nav')
      @include('partials.sidebar')
      <main role="main" class="main-content">
       @yield('content')
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    @include('partials.js')
</body>
</html>

