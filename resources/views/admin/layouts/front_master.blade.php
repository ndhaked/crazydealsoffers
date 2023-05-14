<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>@yield('title', config('app.name'))</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon-32x32.png') }}">
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="hold-transition login-page">
     <section>
        @yield('content')
     </section>
     @include('admin.page.message')
     <script src="{{ asset('js/admin.js') }}" defer></script>
     <script type="text/javascript">
            var site_url = '{{ URL::to("/") }}';
            var _imageUpload = "";
            var _UserImgSrc = "{{URL::to('storage/users/')}}/";
            var _UserImgThumbSrc = "{{URL::to('storage/users/thumbnail')}}/";
            var _publicPath = "{{url('/')}}";
            var REQUEST_URL = "{{Request::url()}}";
            var admin_url = "{{ URL::to('/') }}/admin";
            var _enter_same_as_passowed = "{{trans('menu.validiation.please_enter_confirm_password_same_as_password')}}";
            var must_minimum_digit_pwd = "{{trans('menu.validiation.password_must_be_minimum_8_digit')}}";
            var verify_you_are_human = "{{trans('menu.validiation.verify_you_are_human')}}";
            var enter_correct_email = "{{trans('menu.validiation.enter_correct_email')}}";
            var enter_valid_card_number = "{{trans('menu.validiation.enter_valid_card_number')}}";
      </script>
     @yield('uniquePageScript')
     @yield('script')     
</body>
</html>