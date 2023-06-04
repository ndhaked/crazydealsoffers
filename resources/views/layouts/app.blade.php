<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" style="display: block;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="index,follow" />
    <link rel="canonical" @if(\Request::route() && \Request::route()->getName()=='home') href="{{ url()->current() }}/" @else href="{{ url()->current() }}" @endif>
    <meta name ="description" content="@yield('description', 'Save more with Coupons, and browse a wide range of coupons from top brands at CN Deals & Coupons. Shop online with CN Deals to save big every day.')">
    <meta name="author" content="" />
    <meta name="copyright" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon-32x32.png') }}">

    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('og_title', config('app.name', 'Laravel'))" />
    <meta property="og:site_name" content="{{config('app.name', 'Laravel')}}" />
    <meta property="og:description" content="@yield('description', 'Save more with Coupons, and browse a wide range of coupons from top brands at CN Deals & Coupons. Shop online with CN Deals to save big every day.')" />
    <meta property="og:type" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('/front/images/logo.svg') }}" />
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title',config('app.name', 'Laravel'))</title>
	
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/owl.theme.default.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('lobibox/css/lobibox.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('css/developer.css') }}" rel="stylesheet">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4941797353264345"
     crossorigin="anonymous"></script>
     <meta name="google-site-verification" content="R_vcvKqTGZq6XVdb_YoNc0DJ8nsMsCTAYRUkmV_6yRA" />
</head>
<body>
    <div id="app">
        @include('layouts.header')
        @yield('content')
        @include('layouts.footer')
    </div>   
    <div class="copyright">
        <div class="container">
            <div class="row">
            <div class="col-sm-12">
                <span>
                Copyright © {{date('Y')}} All Right Reserved - CN Deals & Coupons
                </span>
            </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="{{ asset('front/js/owl.carousel.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{ asset('front/js/custom.js') }}"></script>
        <!-- ------------------------------------------------------------------ -->
        {!! Html::script('lobibox/lib/jquery.1.11.min.js') !!} 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {!! Html::script('lobibox/js/lobibox.js') !!}
        @if(isset($errors))
            @if ($errors->any())
                <script>
                $(function () {
                        (function () {
                            Lobibox.notify('error', {
                                rounded: false,
                                delay: 5000,
                                delayIndicator: true,
                                position: "top right",
                                msg: "<?php foreach($errors->all() as $error){ echo $error."<br>"; } ?>"
                            });
                        })();
                    });
                </script>
            @endif
        @endif
            @if(session()->has('success') or session()->has('warning') or session()->has('info') or session()->has('danger') or session()->has('error'))
                @foreach (['danger', 'warning', 'success', 'info','error'] as $msg)
                    @if(session()->has($msg))
                        <script>
                        var msgType = "{{$msg}}"
                        $(function () {
                                (function () {
                                    Lobibox.notify(msgType, {
                                        rounded: false,
                                        delay: 10000,
                                        delayIndicator: true,
                                        position: "top right",
                                        msg: "{{ session()->get($msg)}}"
                                    });
                                })();
                            });
                        </script>
                        <?php  session()->forget($msg); ?>
                    @endif
                @endforeach
            @endif
        @if (session('status'))
        <script>
            var msgType = "success"
            $(function () {
                (function () {
                    Lobibox.notify(msgType, {
                        rounded: false,
                        delay: 4000,
                        delayIndicator: true,
                        position: "top right",
                        msg: "{{ session('status') }}"
                    });
                })();
            });
        </script>
        @endif
        <style type="text/css">
        .lobibox-notify-msg{max-height:100px !important;}
        </style>
    <!-- ------------------------------------------------------------------ -->
          
    <script>
        $(function() {
            $("form[name='subscribe']").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    email: "Please enter a valid email address"
                },
                submitHandler: function(form) {
                    // form.submit();
                    $('#myButton').prop('disabled', true);
                    let email =  $("#email").val();
                    $.ajax({
                        method: "POST",
                        url: "{{route('subscribe')}}",
                        data: {_token:'{{csrf_token()}}','email':email }
                    })
                    .done(function( response ) {
                        $('#myButton').prop('disabled', false);
                        $("#email").val('');

                        let msgType = response.status
                        $(function () {
                            (function () {
                                Lobibox.notify(msgType, {
                                    rounded: false,
                                    delay: 4000,
                                    delayIndicator: true,
                                    position: "top right",
                                    msg: response.message
                                });
                            })();
                        });
                    });
                }
            });
        });

        $(function() {
            $("form[name='subscribe_footer']").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    email: "Please enter a valid email address"
                },
                submitHandler: function(form) {
                    // form.submit();
                    $('#myButton').prop('disabled', true);
                    let email =  $("#email_footer").val();
                    $.ajax({
                        method: "POST",
                        url: "{{route('subscribe')}}",
                        data: {_token:'{{csrf_token()}}','email':email }
                    })
                    .done(function( response ) {
                        $('#myButton').prop('disabled', false);
                        $("#email").val('');

                        let msgType = response.status
                        $(function () {
                            (function () {
                                Lobibox.notify(msgType, {
                                    rounded: false,
                                    delay: 4000,
                                    delayIndicator: true,
                                    position: "top right",
                                    msg: response.message
                                });
                            })();
                        });
                    });
                }
            });
        });
    </script>
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();

            var msgType = "success"
            $(function () {
                (function () {
                    Lobibox.notify(msgType, {
                        rounded: false,
                        delay: 4000,
                        delayIndicator: true,
                        position: "top right",
                        msg: "Copied..."
                    });
                })();
            });
        }
    </script>
    @yield('script')

    <script type="module">
      // Import the functions you need from the SDKs you need
      import { initializeApp } from "https://www.gstatic.com/firebasejs/9.8.4/firebase-app.js";
      import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.8.4/firebase-analytics.js";
      // TODO: Add SDKs for Firebase products that you want to use
      // https://firebase.google.com/docs/web/setup#available-libraries

      // Your web app's Firebase configuration
      // For Firebase JS SDK v7.20.0 and later, measurementId is optional
      const firebaseConfig = {
        apiKey: "AIzaSyDErlTKQvS07U5MfNN12TkjlhRG0qEfe_Q",
        authDomain: "cndealsandcoupons-93542.firebaseapp.com",
        projectId: "cndealsandcoupons-93542",
        storageBucket: "cndealsandcoupons-93542.appspot.com",
        messagingSenderId: "1008570674188",
        appId: "1:1008570674188:web:2c1dddb2aac9e91318d229",
        measurementId: "G-JM8GF4NV4L"
      };

      // Initialize Firebase
      const app = initializeApp(firebaseConfig);
      const analytics = getAnalytics(app);
    </script>
</body>
</html>
