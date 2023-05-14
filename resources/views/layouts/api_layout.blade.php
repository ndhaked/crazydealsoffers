<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="author" content="">
  <title>@yield('title', config('app.name'))</title>
  <meta name="description" content="@yield('description')">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{URL::to('assets/admin/images/favicon.ico')}}" type="image/png">
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body style="background: #fff;">
    <section style="padding: 4px 30px 4px 30px;">
             @yield('content')
    </section>
</body>
</html>